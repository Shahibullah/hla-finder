<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LabHlaController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AccountController;


Route::post('/theme/toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');



Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/conditions', [PageController::class, 'conditions'])->name('conditions');

Route::middleware(['auth', 'role:donor,receiver'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth', 'role:lab'])->group(function () {
    Route::get('/lab/hla', [LabHlaController::class, 'index'])->name('lab.hla.index');
    Route::post('/lab/hla/update/{id}', [LabHlaController::class, 'update'])->name('lab.hla.update');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users/{id}/activate', [AdminUserController::class, 'activate'])->name('admin.users.activate');
    Route::post('/admin/users/{id}/deactivate', [AdminUserController::class, 'deactivate'])->name('admin.users.deactivate');
});

Route::middleware('auth')->post('/account/deactivate', [AccountController::class, 'deactivate'])->name('account.deactivate');




/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'home'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Role Based Dashboards
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/donor/dashboard', [DashboardController::class, 'dashboard'])
        ->middleware('role:donor')
        ->name('donor.dashboard');

    Route::get('/receiver/dashboard', [DashboardController::class, 'dashboard'])
        ->middleware('role:receiver')
        ->name('receiver.dashboard');

    Route::get('/lab/dashboard', [DashboardController::class, 'dashboard'])
        ->middleware('role:lab')
        ->name('lab.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin Lab Management
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/labs', [AdminController::class, 'labs'])
            ->name('admin.labs');

        Route::post('/admin/labs/{id}/activate', [AdminController::class, 'activateLab'])
            ->name('admin.activateLab');

        Route::post('/admin/labs/{id}/deactivate', [AdminController::class, 'deactivateLab'])
            ->name('admin.deactivateLab');
    });
});