<?php

namespace Xetreon\RolePermission;

use App\Models\User;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Xetreon\RolePermission\Exceptions\AuthException;
use Exception;

trait RolePermission
{
    /**
     * @param string $method
     * @return bool
     */
    public function checkRole(string $method): bool
    {
        $user = $this->getUser();
        $role = $user->roles;
        if(sizeof($role) < 1)
        {
            $exception = config('role-permission.permission_exception');
            throw new $exception("You dont have permission to perform this operation");
        }
        if(config('role-permission.allow_super_admin_overwrite_all') && $role[0]->code == config('role-permission.super_admin_role'))
        {
            return true;
        }

        $methodPermission = $this->getMethodPermission()."@".$method;
        if($role[0]->permissions()->where('permission', $methodPermission)->exists()){
            return true;
        } else {
            return $this->handleReturn();
        }
    }

    /**
     * @return bool
     */
    public function handleReturn(): bool
    {
        if(config('role-permission.throw_exception_on_false_permission')){
            $exception = config('role-permission.permission_exception');
            throw new $exception("You dont have permission to perform this operation");
        } else {
            return false;
        }
    }

    public function getMethodPermission(){
        $className = debug_backtrace()[2]['class'];
        $className = str_replace('\\',"/", $className);
        return str_replace('App/Http/Controllers'.config('role-permission.controller_directory_suffix').'/',"", $className);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        $exception = config('role-permission.permission_exception');
        try {
            $driver = config('role-permission.auth_driver');
            $driver = new $driver;
            $user = $driver->getAuth();
            if(empty($user))
            {
                throw new AuthException("Unable to find user");
            }
            return $user;
        } catch (Exception){
            throw new $exception("User does not have a role");
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            throw new $exception($e->getMessage());
        }
    }

}
