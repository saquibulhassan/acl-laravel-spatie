<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::paginate(15);

        return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissionModules = Permission::where('parent_permission', 0)->get()->groupBy('module');
        $rolePermissions = collect([]);

        return view('role.form', compact('permissionModules', 'rolePermissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles',
            'permissions' => 'required',
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->save();

        $childPermissions = Permission::whereIn('parent_permission', $request->permissions)->pluck('id')->toArray();
        $permissions = array_merge($request->permissions, $childPermissions);

        $role->givePermissionTo($permissions);

        Alert::success('Role added');

        return redirect(route('role.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param \Spatie\Permission\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Spatie\Permission\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissionModules = Permission::where('parent_permission', 0)->get()->groupBy('module');
        $rolePermissions = $role->getAllPermissions()->pluck('id');

        return view('role.form', compact('role', 'permissionModules', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Spatie\Permission\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,' . $role->id . ',id'
        ]);

        $role->name = $request->name;
        $role->save();

        $childPermissions = Permission::whereIn('parent_permission', $request->permissions)->pluck('id')->toArray();
        $permissions = array_merge($request->permissions, $childPermissions);

        $role->syncPermissions($permissions);

        Alert::success('Role updated');

        return redirect(route('role.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Spatie\Permission\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->permissions()->detach();
        $role->delete();

        Alert::success('Role deleted');

        return redirect(route('role.index'));
    }
}
