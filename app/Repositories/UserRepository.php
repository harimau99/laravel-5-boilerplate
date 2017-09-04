<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\App;
use InfyOm\Generator\Common\BaseRepository;
use App\Repositories\RoleRepository;
use App\Repositories\PermissionRepository;
use Illuminate\Container\Container as Application;

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email'
    ];

    protected $roleRepository;

    protected $permissionRepository;
    /**
     * Configure the Model
     **/

    public function __construct(Application $app, RoleRepository $roleRepo, PermissionRepository $permissionRepo) {
        $this->roleRepository = $roleRepo;
        $this->permissionRepository = $permissionRepo;

        parent::__construct($app);
    }

    public function model()
    {
        return User::class;
    }

    public function getAllByRole($sort) {
        $roles = $this->roleRepository->findWhere([["sort",">=", $sort]]);
        $arr = [];
        foreach($roles as $role){
            $arr[] = $role->users[0];
        }
        return $arr;
    }

    public function create(array $attributes) {
        $arrUser = $attributes;
        $attributes['password'] = bcrypt($arrUser['password']);

        unset($attributes['roles'], $attributes['permissions']);
        if ( $user = $this->model->create($attributes) ) {
            $this->syncPermissions($arrUser, $user);
            return $user;
        } else {
            throw new Exception("Cannot create User");
        }
    }

    public function update(array $attributes, $id)
    {
        //$user = $this->model()
        $user = $this->findWithoutFail($id);
        if (empty($user)) {
            throw new Exception("Cannot find User");
        }
        $arrUser = $attributes;
        unset($attributes['roles'], $attributes['permissions'], $attributes['password']);
        $user->fill($attributes);

        if(isset($attributes['password'])){
            $user->password = bcrypt($attributes['password']);
        }
        $user->save();
        $this->syncPermissions($arrUser, $user);
        return $user;
    }

    private function syncPermissions(array $attributes, $user)
    {
        // Get the submitted roles
        // Get the roles
        //dd($attributes['roles']);

        $roles = $this->roleRepository->find($attributes['roles']);
        //dd($roles->id);
        $permissions = [];
        if(isset($attributes['permissions']))
            $permissions = $attributes['permissions'];


        // check for current role changes
        if( ! $user->hasAllRoles( $roles ) ) {
            // reset all direct permissions for user
            $user->permissions()->sync([]);
        } else {
            // handle permissions
            $user->syncPermissions($permissions);
        }

        $user->syncRoles($roles);
        return $user;
    }
}
