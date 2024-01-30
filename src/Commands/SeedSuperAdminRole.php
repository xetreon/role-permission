<?php

namespace Xetreon\RolePermission\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Xetreon\RolePermission\Models\Role;
use Exception;

class SeedSuperAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xetreon:seedRole {user?}';

    protected array $config;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the super admin role for the project';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        $this->config = config('role-permission');
        Log::info("Seeding SuperAdmin");
        /** @var Role $role */
        $role = Role::query()->firstOrCreate(
            ['code' =>  $this->config['super_admin_role']],
            ['name' => "Super Admin"]
        );
        if(!empty($this->argument('user')))
        {
            $user = User::query()->find($this->argument('user'));
            if(empty($user))
            {
                throw new Exception("Unable to find User with id : ".$this->argument('user'));
            }
            $user->addRole($role->id);
        }

    }
}
