<?php
/*
 * If you are using standard passport then user
 *
 * */
return [
    'super_admin_role' => 'super_admin',
    'allow_super_admin_overwrite_all' => false,
    'permissions' => ['index', 'create', 'update', 'destroy'],
    'controller_directory_suffix' => '/v1', // If we want to generate permission for a specific folder
    'exclude' => [],
    'auth_driver' => Xetreon\RolePermission\Core\DefaultAuthDriver::class,
    'throw_exception_on_false_permission' => false,
    'permission_exception' => Xetreon\RolePermission\Exceptions\PermissionException::class
];
