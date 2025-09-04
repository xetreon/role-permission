<?php
/*
 * If you are using standard passport then user
 *
 * */
return [
    /**
     * code of the super admin role
     */
    'super_admin_role' => 'super_admin',

    /**
     * If true, then we dont need to setup permission for super admin role. By default, super admin can access all resources.
     * If false, then we need to setup role for the super admin
    */
    'allow_super_admin_overwrite_all' => false,

    /**
     * All the possible permission for each resource. please add permission as required.
     */
    'permissions' => ['index', 'create', 'update', 'destroy'],

    /**
     * By default, it will look for the controlles inside App\Http\Controllers\v1 folder.
     * if you are going to keep the controllers inside the controller folder directly then make this empty string
     */
    'controller_directory_suffix' => '/v1', // If we want to generate permission for a specific folder

    /**
     * All the controller you dont want to generate permisison fop.
     * Usually Auth, Password reset or any other controller that is for public, we dont want to generate permission for.
     * For that only pass the relative path inside the controller_directory
     * For example, if the controller path is /var/www/project/app/Http/Controllers/v1/Auth/AuthController.php, then you need to add
     * Auth/AuthController in the exlude array. No need to add the extension as well
     */
    'exclude' => [],

    /**
     * By default, this will use laravel's auth driver. If you are using some custom driver enter the path here.
     * The driver must return the User collection.
     */
    'auth_driver' => Xetreon\RolePermission\Core\DefaultAuthDriver::class,

    /**
     * If you enable this, the permission module only will throw the exception. If you keep it false, the permission module will retun false when you check for permission.
     */
    'throw_exception_on_false_permission' => false,

    /**
     * If you have throw_exception_on_false_permission  as true, then this will be the exception that the module will throw. If you want to use any custom exception class, define it here.
     */
    'permission_exception' => Xetreon\RolePermission\Exceptions\PermissionException::class
];
