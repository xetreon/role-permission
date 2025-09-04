<?php

namespace Xetreon\RolePermission\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Xetreon\RolePermission\Models\Permission;

class GeneratePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xetreon:permissions';

    protected array $allFiles = [];
    protected array $config = [];
    protected string $basePath = "";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all possible permissions for the project';

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
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->config = config('role-permission');
        Log::info("Seeding All Permissions");
        $this->basePath = app_path('Http/Controllers'.$this->config['controller_directory_suffix']);


        $this->listFolderFiles($this->basePath, "");

        $this->insertPermission();

    }

    /**
     * @return void
     */
    public function insertPermission(): void
    {
        foreach ($this->allFiles as $files) {
            Log::info("Seeding All Permissions for ".$files);
            foreach ($this->config['permissions'] as $op) {
                $permName = $files.'@'.$op;
                Log::info("Creating Permission : ".$permName);
                $existPerm = Permission::query()->where('name', $permName)->first();
                if(empty($existPerm))
                {
                    $existPerm = new Permission();
                    $existPerm->name = $permName;
                    $existPerm->save();
                    Log::info("Created Permission : ".$permName);
                } else {
                    Log::info("Permission Exist : ".$permName);
                }
            }
        }
    }

    /**
     * @param $file
     * @return string
     */
    public function formatFileName($file): string
    {
        $file = str_replace($this->basePath.'/', "", $file);
        $file = str_replace('.php', "", $file);
        if(str_starts_with($file, '/'))
        {
            $file = substr($file, 1);
        }

        return $file;
    }

    /**
     * @param $dir
     * @param $prefix
     * @return void
     */
    public function listFolderFiles($dir, $prefix): void
    {
        $ffs = scandir($dir);
        unset($ffs[array_search(".", $ffs, true)]);
        unset($ffs[array_search("..", $ffs, true)]);

        // prevent empty ordered elements
        if (count($ffs) < 1)
            return;

        foreach($ffs as $ff){
            if(is_dir($dir.'/'.$ff)){
                $this->listFolderFiles($dir.'/'.$ff, $dir.'/'.$ff);
            } else {
                $fileName = $this->formatFileName($prefix.'/'.$ff);
                if(!in_array($fileName, $this->config['exclude'])){
                    $this->allFiles[] = $fileName;
                }
            }
        }
    }
}
