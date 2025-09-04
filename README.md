composer require xetreon/role-permission

add Xetreon\RolePermission\RolePermissionServiceProvider::class to config/app.php
For laravel 12, add Xetreon\RolePermission\RolePermissionServiceProvider::class to bootstrap/providers.php 

php artisan vendor:publish --provider="Xetreon\RolePermission\RolePermissionServiceProvider"

run php artisan migrate

php artisan xetreon:permissions

php artisan xetreon:seedRole {user_id}

Add permissible trait in User Model
use Xetreon\RolePermission\Traits\Permissible;


use Permissible;
