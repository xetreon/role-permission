# Xetreon Role Permission for Laravel

A lightweight and flexible **role and permission management package** for Laravel. It allows you to manage roles, assign permissions, and automatically generate permissions from your controllers. Supports super admin bypass and custom authentication drivers.

---

## Installation

Install via Composer:

```bash
composer require xetreon/role-permission
```

## Laravel 12 Integration

1. **Add the Service Provider**

For Laravel 12 (add in `bootstrap/providers.php`):

```
Xetreon\RolePermission\RolePermissionServiceProvider::class,
```
For older Laravel versions (add in config/app.php):
```
Xetreon\RolePermission\RolePermissionServiceProvider::class,
```
2. **Add the Service Provider**
```
artisan vendor:publish --provider="Xetreon\RolePermission\RolePermissionServiceProvider"
```
3. **Add the Service Provider**
```
php artisan migrate
```
## Configuration
The configuration file role-permission.php contains the following options:

```
return [
    /**
     * code of the super admin role
     */
    'super_admin_role' => 'super_admin',

    /**
     * If true, then we dont need to setup permission for super admin role. By default, super admin can access all resources.
     * If false, then we need to setup role for the super admin
    */
    'allow_super_admin_overwrite_all' => true,

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
    'exclude' => ['Auth/AuthController', 'BaseController'],

    /**
     * By default, this will use laravel's auth driver. If you are using some custom driver enter the path here.
     * The driver must return the User collection.
     */
    'auth_driver' => Xetreon\RolePermission\Core\JwtAuthDriver::class,

    /**
     * If you enable this, the permission module only will throw the exception. If you keep it false, the permission module will retun false when you check for permission.
     */
    'throw_exception_on_false_permission' => true,

    /**
     * If you have throw_exception_on_false_permission  as true, then this will be the exception that the module will throw. If you want to use any custom exception class, define it here.
     */
    'permission_exception' => \App\Exceptions\PermissionException::class,
];
```

**Explanation of Key Options:**
```
super_admin_role – Code for the super admin role.

allow_super_admin_overwrite_all – If true, super admin bypasses permission checks.

permissions – List of actions for resources. Extend as needed.

controller_directory_suffix – Folder suffix where controllers are located; used when generating permissions.

exclude – Controllers to skip when generating permissions (e.g., Auth, BaseController).

auth_driver – Custom authentication driver if Laravel default is not used.

throw_exception_on_false_permission – Toggle exception throwing for unauthorized access.

permission_exception – Custom exception class to throw when permission fails.
```
## Usage
1. **Add Trait to User Model**
```php
use Xetreon\RolePermission\Traits\Permissible;



class User extends Authenticatable
{
    use Permissible;
}
```

## Artisan Commands
1. **Generate All Permissions**
```
php artisan xetreon:permissions
```
Scans the configured controller directory and
Generates permissions for all controller methods (index, create, update, destroy by default).
This also Skips controllers listed in exclude.

2. **Seed Super Admin Role**
```
php artisan xetreon:seedRole {user_id?}
```
Seeds the Super Admin role in your database and assign a user as Super Admin by providing their ID.
Automatically bypasses all permission checks if ```allow_super_admin_overwrite_all``` is true.

## Methods
```$user->addRole($role)``` – Assigns a role to a user.

```$user->removeRole()``` – Removes all roles from a user.

```$user->roles()``` – Returns all roles of the user.

```$user->getRole()``` – Returns the first assigned role.

## Query scopes

```$user->whereHasRole($role)``` – Filter users by role code.

```$user->whereHasRoleId($roleId)``` – Filter users by role ID.

## Check Permissions in Controllers
**Add this to any controller to check user permissions:**
```
use Xetreon\RolePermission\RolePermission;

class PostController extends Controller
{
    use RolePermission;

    public function update()
    {
        $this->checkRole('update'); // Checks if the user has permission
        // Your update logic here
    }
}
```

**Allowed Methods in controller**

```$this->checkRole($method)``` – Checks if the authenticated user has permission for the method.



## Core Authentication Drivers
```JwtAuthDriver``` – Default driver using your Auth::getAuth() method.

```DefaultAuthDriver``` – Uses Laravel's built-in Auth::user().

You can create a custom driver by implementing a getAuth() method that returns a User instance. and you can update the newly created driver in `auth_driver` field in the `config.php`

## Migrations
The package provides migrations for:
```
roles – Stores all roles.
permissions – Stores all permissions.
user_roles – Pivot table for user-role relationship.
role_permissions – Pivot table for role-permission relationship.
```
## Logging
Both commands (xetreon:permissions and xetreon:seedRole) log actions using Laravel's default logging system (Log::info()).

## Exception Handling
By default, permission failure return false. 
If ```throw_exception_on_false_permission``` is false, checkRole() simply returns false.
If you want to throws the exception defined in permission_exception config, set ```throw_exception_on_false_permission``` as true

## Notes
- Super Admin automatically bypasses all permission checks if ```allow_super_admin_overwrite_all``` is true.

- Always run php artisan xetreon:permissions after adding new controllers or actions.

- Excluded controllers are ignored during permission generation. For example, if the controller path is `/var/www/project/app/Http/Controllers/v1/Auth/AuthController.php`, then you need to add `Auth/AuthController` in the exlude array. No need to add the extension as well.


## Example Workflow
- Install package and publish config.
- Run migrations.
- Seed super admin role: `php artisan xetreon:seedRole 1`
-Generate permissions: `php artisan xetreon:permissions`
- Assign roles to users:
```
$user = User::find(2);
$user->addRole($roleId);
```
- Check permissions in controllers using RolePermission trait:
```
$this->checkRole('update');
```
## License
MIT License
