<?php
/** @noinspection PhpUndefinedFieldInspection */

namespace Xetreon\RolePermission\Traits;

use Xetreon\RolePermission\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Exception;
use Illuminate\Database\Eloquent\Builder;

trait Permissible
{
    /**
     * @param $role
     * @return bool
     * @throws Exception
     */
    public function addRole($role): bool
    {
        if($this->roles()->exists())
        {
            throw new Exception("Selected user already has a role");
        }
        $this->roles()->attach([$role]);
        return true;
    }

    /**
     * @return bool
     */
    public function removeRole(): bool
    {
        if($this->roles()->exists())
        {
            $this->roles()->detach();
        }
        return true;
    }

    /**
     * @param Builder $query
     * @param $role
     * @return void
     */
    public function scopeWhereHasRole(Builder $query, $role): void
    {
        $query->whereHas('roles', function ($q) use ($role)
        {
            $q->where('roles.code', $role);
        });
    }

    /**
     * @param Builder $query
     * @param $role
     * @return void
     */
    public function scopeWhereHasRoleId(Builder $query, $role): void
    {
        $query->whereHas('roles', function ($q) use ($role)
        {
            $q->where('roles.id', $role);
        });
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }

    /**
     * @return Role|null
     */
    public function getRole(): ?Role
    {
        if($this->roles()->exists()){
            return $this->roles[0];
        }
        return null;
    }
}
