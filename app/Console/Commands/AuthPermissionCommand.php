<?php

namespace App\Console\Commands;

use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use Illuminate\Console\Command;

class AuthPermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:permission {name} {--R|remove}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create auth permissions for a model';

    protected $permissionRepository;

    protected $roleRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PermissionRepository $permissionRepo, RoleRepository $roleRepo)
    {
        $this->permissionRepository = $permissionRepo;
        $this->roleRepository = $roleRepo;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $permissions = $this->generatePermissions();
        // check if its remove
        if( $is_remove = $this->option('remove') ) {
            // remove permission
            if( $this->permissionRepository->findWhere([['name', 'LIKE', '%'. $this->getNameArgument()]])->delete() ) {
                $this->warn('Permissions ' . implode(', ', $permissions) . ' deleted.');
            }  else {
                $this->warn('No permissions for ' . $this->getNameArgument() .' found!');
            }
        } else {
            // create permissions
            foreach ($permissions as $permission) {
                $this->permissionRepository->firstOrCreate(['name' => $permission ]);
            }
            $this->info('Permissions ' . implode(', ', $permissions) . ' created.');
        }
        // sync role for admin
        if( $role = $this->roleRepository->findWhere([['sort', "=", 1]])->first() ) {
            $role->syncPermissions($this->permissionRepository->all());
            $this->info('Admin permissions updated.');
        }
    }

    /**
     * Build permissions from name
     *
     * @return array
     */
    private function generatePermissions()
    {
        $abilities = ['view', 'add', 'edit', 'delete'];
        $name = $this->getNameArgument();
        return array_map(function($val) use ($name) {
            return $val . '_'. $name;
        }, $abilities);
    }
    /**
     * Get pluralized name argument
     *
     * @return string
     */
    private function getNameArgument()
    {
        return strtolower(str_plural($this->argument('name')));
    }
}
