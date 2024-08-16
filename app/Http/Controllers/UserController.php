<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

//use Inertia\Middleware;
//use Spatie\Permission\Middleware\PermissionMiddleware;
//use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use AuthorizesRequests;
//    public static function middleware(): array
//    {
//        return [
//                new Middleware(PermissionMiddleware::using('User Create'), only: ['create', 'store']),
//                new Middleware(PermissionMiddleware::using('User Show'), only: ['show', 'index']),
//                new Middleware(PermissionMiddleware::using('User Edit'), only: ['edit', 'update']),
//                new Middleware(PermissionMiddleware::using('User Delete'), only: ['delete']),
//        ];
//    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view', new User());
        $users = User::with('roles')->get();
        return inertia('User/Index', [
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames(),
                ];
            }),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', new User());
        $roles = Role::all();
        return inertia('User/Create',[
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', new User());
         $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
             'rolesValue' => 'required'
        ]);

         $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => Hash::make($request->password)
         ]);

         $user->syncRoles($request->rolesValue);

//         return redirect()->route('user.create')->with('success', 'User created successfully.')->withErrors($request->all());

         return to_route('user.index')->with('success', 'User created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
      return inertia('User/Show', [
          'user' => $user
      ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return inertia('User/Edit', [
            'user' => $user->load('roles'),
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
//        return $request;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|confirmed|min:6',
            'rolesValue' => 'required'
        ]);
        $data = [
            'name' => $request->name,
            'email' => $request->email
        ];
        if (!empty($request->password)) {
            $data += [
                'password' => Hash::make($request->password)
            ];
        }
       $user->update($data);
        $user->syncRoles($request->rolesValue);

       return to_route('user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return to_route('user.index')->with('success', 'User deleted successfully.');

    }
}
