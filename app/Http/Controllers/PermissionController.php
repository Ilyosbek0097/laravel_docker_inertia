<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
//use Inertia\Middleware;
//use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use AuthorizesRequests;
//    public static function middleware(): array
//    {
//        return [
//            new Middleware(PermissionMiddleware::using('Permission Create'), only: ['create', 'store']),
//            new Middleware(PermissionMiddleware::using('Permission Show'), only: ['show', 'index']),
//            new Middleware(PermissionMiddleware::using('Permission Edit'), only: ['edit', 'update']),
//            new Middleware(PermissionMiddleware::using('Permission Delete'), only: ['delete']),
//        ];
//    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view', new Permission());
        return inertia('Permission/Index', [
            'permissions' => Permission::orderBy('id', 'desc')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', new Permission());
        return inertia('Permission/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Permission());
        $request->validate([
            'name' => 'required|unique:permissions,name|max:255',
        ]);
        $permission = Permission::create([
            'name' => $request->name
        ]);
        return to_route('permission.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);
        return inertia('Permission/Show', [
            'permission' => $permission
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $this->authorize('update', $permission);
        return inertia('Permission/Edit', [
            'permission' => $permission
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', $permission);
        $request->validate([
            'name' => 'required|max:255|unique:permissions,name,'.$permission->id,
        ]);
        $permission->update([
            'name' => $request->name,
        ]);
        return to_route('permission.index')->with('success', 'Permission updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);
        $permission->delete();
        return to_route('permission.index')->with('success', 'Permission deleted successfully.');
    }
}
