<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ErrorLogController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login.view');

    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/forgot-password', [AuthController::class, 'forgotPasswordView'])->name('password.request');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/users/{user}/impersonate', [UserController::class, 'impersonate'])
        ->name('users.impersonate');

    Route::post('/impersonate/leave', [UserController::class, 'leaveImpersonate'])
        ->name('users.impersonate.leave');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::middleware(['auth', 'block.impersonation.actions'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('menus', MenuController::class);

    Route::get('/logs/activity', [ActivityLogController::class, 'index'])
        ->name('logs.activity');
    Route::get('logs/error', [ErrorLogController::class, 'index'])
        ->name('logs.error');

    Route::post('/permissions/store', [RoleController::class, 'storePermission'])
        ->name('permissions.store');

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::group(['prefix' => 'settings'], function () {
        Route::resource('organizations', OrganizationController::class);
    });
});

Route::fallback(function () {
    abort(404);
});

Route::get('/test', function () {
    return view('errors.coming_soon');
});
