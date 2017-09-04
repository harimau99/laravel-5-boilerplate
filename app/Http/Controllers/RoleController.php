<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Traits\Authorizable;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Flash;

class RoleController extends AppBaseController
{
    use Authorizable;

    private $roleRepository;

    private $permissionRepository;

    public function __construct(RoleRepository $roleRepo, PermissionRepository $permissionRepo) {
        $this->roleRepository = $roleRepo;
        $this->permissionRepository = $permissionRepo;
    }

    public function index(Request $request) {
        $this->roleRepository->pushCriteria(new RequestCriteria($request));

        $roles = $this->roleRepository->all();
        $permissions = $this->permissionRepository->all();

        return view('roles.index')
            ->with('roles', $roles)
            ->with('permissions', $permissions);
    }

    public function store(CreateRoleRequest $request)
    {
        $input = $request->only('name','sort');

        $role = $this->roleRepository->create($input);

        Flash::success('Role saved successfully.');

        return redirect(route('roles.index'));
    }

    public function update($id, Request $request)
    {
        $role = $this->roleRepository->findWithoutFail($id);

        if (empty($role)) {
            Flash::error('Role not found');

            return redirect(route('roles.index'));
        }

        $role = $this->roleRepository->update($request->all(), $id);

        Flash::success('Role updated successfully.');

        return redirect(route('roles.index'));
    }
}
