<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\WebLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

// Login routes
Route::get('/login', [WebLoginController::class, 'show'])->name('admin.login');
Route::post('/login', [WebLoginController::class, 'login'])->name('admin.login.post');

// Protected admin routes
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/couples', [AdminController::class, 'couples'])->name('admin.couples');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');
    Route::post('/logout', [WebLoginController::class, 'logout'])->name('admin.logout');
});
