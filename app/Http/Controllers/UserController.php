<?php

namespace App\Http\Controllers;

use App\Http\Traits\Authorizable;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use Flash;

class UserController extends AppBaseController
{
    use Authorizable;
    //
    private $userRepository;

    private $roleRepository;

    private $permissionRepository;

    public function __construct(UserRepository $userRepo, RoleRepository $roleRepo, PermissionRepository $permissionRepo) {
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepo;
        $this->permissionRepository = $permissionRepo;
    }

    public function index(Request $request) {
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        if(Auth::user()->roles[0]->sort > 1)
            $users = $this->userRepository->getAllByRole(Auth::user()->roles[0]->sort);
        else
            $users = $this->userRepository->all();

        return view('users.index')
            ->with('users', $users);
    }

    public function create()
    {
        //return Auth::user()->roles[0]->name;
        $roles = $this->roleRepository->getLevelUp(Auth::user()->roles[0]->sort);
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created Post in storage.
     *
     * @param CreatePostRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified Post.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified Post.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $roles = $this->roleRepository->getLevelUp(Auth::user()->roles[0]->sort);
        if(Auth::user()->roles[0]->sort > 1){
            $permissions = [];
        }else{
            $permissions = $this->permissionRepository->all('name','id');
        }

        //return $permissions;

        return view('users.edit')->with('user', $user)
            ->with('roles', $roles)
            ->with('permissions', $permissions);
    }

    /**
     * Update the specified Post in storage.
     *
     * @param  int              $id
     * @param UpdatePostRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $user = $this->userRepository->update($request->all(), $id);

        Flash::success('User updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified Post from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }
}
