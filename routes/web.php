<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
Route::group(['middleware' => ['role:Admin|Superadmin|User|Superuser|Meneger|']], function () {
    Route::resource('role', RoleController::class);
    Route::get('/role/permission/{role}', [RoleController::class, 'permission'])->name('role.permission');
    Route::put('/role/permission/{role}', [RoleController::class, 'addPermission'])->name('role.permission.add');
    Route::resource('user', UserController::class);
    Route::resource('permission', PermissionController::class);

    // Open Ai Chat Bot
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'chat'])->name('chat.action');


});

Route::get('/errors/forbidden', function (){
    return Inertia::render('Errors/Forbidden', [
        'message' => __('Forbidden'),
    ]);
})->name('errors.forbidden');
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
