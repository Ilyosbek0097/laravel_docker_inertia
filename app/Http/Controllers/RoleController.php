<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('Role Create'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('Role Show'), only: ['show', 'index']),
            new Middleware(PermissionMiddleware::using('Role Edit'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('Role Delete'), only: ['delete']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get(); // Rollarni va ularning ruxsatlarini olish

        return inertia('Roles/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Roles/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
           'name' => 'required|max:100|unique:roles,name',
       ]);
       Role::create([
           'name' => $request->name,
       ]);

       return to_route('role.index')->with('success', 'Role created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return inertia('Roles/Show', [
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return inertia('Roles/Edit', [
            'role' => $role,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|max:100|unique:roles,name,'.$role->id,
        ]);
        $role->update([
            'name' => $request->name,
        ]);
        return to_route('role.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return to_route('role.index')->with('success', 'Role deleted successfully.');
    }

    public function permission(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
//        return $rolePermissions;
        return Inertia::render('Roles/Permission', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function addPermission(Request $request, Role $role)
    {
        $request->validate([
            'permissionValue' => 'required|array',
        ]);
        $role->syncPermissions($request->permissionValue);
        return to_route('role.index')->with('success', 'Permission added successfully.');


    }
}
