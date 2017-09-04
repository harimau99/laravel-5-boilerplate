<?php

namespace App\Repositories;

use App\Models\Role;
use InfyOm\Generator\Common\BaseRepository;
use Exception;
use Illuminate\Container\Container as Application;

class RoleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    protected $permissionRepository;

    /**
     * Configure the Model
     **/

    public function __construct(Application $app, PermissionRepository $permissionRepo){
        $this->permissionRepository = $permissionRepo;

        parent::__construct($app);
    }

    public function model()
    {
        return Role::class;
    }

    public function getLevelUp($level) {
        return $this->findWhere([["sort",'>=', $level]]);
    }

    public function update(array $attributes, $id)
    {
        //$user = $this->model()
        $role = $this->findWithoutFail($id);
        if (empty($role)) {
            throw new Exception("Cannot find Role");
        }

        if($role->sort === 1){
            $role->syncPermissions($this->permissionRepository->all());
            return $role;
        }

        $permissions = [];
        if(isset($attributes['permissions']))
            $permissions = $attributes['permissions'];

        $role->syncPermissions($permissions);

        return $role;
    }

}
