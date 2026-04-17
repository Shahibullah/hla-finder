<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LabHlaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiverController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/conditions', [PageController::class, 'conditions'])->name('conditions');

Route::post('/theme/toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::post('/account/deactivate', [AccountController::class, 'deactivate'])->name('account.deactivate');

    Route::middleware('role:donor,receiver')->group(function () {
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users/{id}/activate', [AdminUserController::class, 'activate'])->name('admin.users.activate');
        Route::post('/admin/users/{id}/deactivate', [AdminUserController::class, 'deactivate'])->name('admin.users.deactivate');
        Route::get('/admin/labs', [AdminController::class, 'labs'])->name('admin.labs');
        Route::post('/admin/labs/{id}/activate', [AdminController::class, 'activateLab'])->name('admin.activateLab');
        Route::post('/admin/labs/{id}/deactivate', [AdminController::class, 'deactivateLab'])->name('admin.deactivateLab');
        Route::get('/admin/transplant-history', [AdminController::class, 'transplantHistory'])->name('admin.transplant.history');
    });

    Route::middleware('role:donor')->group(function () {
        Route::get('/donor/dashboard', [DashboardController::class, 'dashboard'])->name('donor.dashboard');
        Route::get('/donor/requests', [DonorController::class, 'requests'])->name('donor.requests');
    });

    Route::middleware('role:receiver')->group(function () {
        Route::get('/receiver/dashboard', [DashboardController::class, 'dashboard'])->name('receiver.dashboard');
        Route::get('/receiver/match-status', [ReceiverController::class, 'matchStatus'])->name('receiver.match.status');
        Route::post('/receiver/request/{donor}', [ReceiverController::class, 'requestDonor'])->name('receiver.contact.donor');
    });

    Route::middleware('role:lab')->group(function () {
        Route::get('/lab/dashboard', [DashboardController::class, 'dashboard'])->name('lab.dashboard');
        Route::get('/lab/hla', [LabHlaController::class, 'index'])->name('lab.hla.index');
        Route::post('/lab/hla/update/{id}', [LabHlaController::class, 'update'])->name('lab.hla.update');
        Route::get('/lab/transplant-action', [LabHlaController::class, 'transplantAction'])->name('lab.transplant.action');
        Route::post('/lab/transplant-action', [LabHlaController::class, 'storeTransplantAction'])->name('lab.transplant.action.store');
        Route::get('/lab/transplant-history', [LabHlaController::class, 'transplantHistory'])->name('lab.transplant.history');
    });
});
Route::middleware('role:receiver')->group(function () {
    Route::get('/receiver/dashboard', [DashboardController::class, 'dashboard'])->name('receiver.dashboard');
    Route::get('/receiver/match-status', [ReceiverController::class, 'matchStatus'])->name('receiver.match.status');
    Route::post('/receiver/request/{donor}', [ReceiverController::class, 'requestDonor'])->name('receiver.contact.donor');

    Route::get('/receiver/transplant-history', [ReceiverController::class, 'transplantHistory'])
        ->name('receiver.transplant.history');
});