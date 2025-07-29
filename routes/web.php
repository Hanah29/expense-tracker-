<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CategoryLimitController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Expenses
    Route::resource('expenses', ExpenseController::class);
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Teams
    Route::resource('teams', TeamController::class);
    Route::post('/teams/{team}/join', [TeamController::class, 'join'])->name('teams.join');
    Route::post('/teams/{team}/leave', [TeamController::class, 'leave'])->name('teams.leave');
    
    // Category Limitsa
    Route::get('/category-limits', [CategoryLimitController::class, 'index'])->name('category-limits.index');
    Route::post('/category-limits', [CategoryLimitController::class, 'store'])->name('category-limits.store');
    Route::delete('/category-limits/{categoryLimit}', [CategoryLimitController::class, 'destroy'])->name('category-limits.destroy');
    Route::get('/category-limits/create', [CategoryLimitController::class, 'create'])->name('category-limits.create');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/team', [ReportController::class, 'teamReport'])->name('reports.team');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
