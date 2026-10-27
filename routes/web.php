<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\Dashboard\TeamMemberController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', PortfolioController::class)->name('portfolio');

Route::post('/inquiries', InquiryController::class)
    ->middleware('throttle:6,1')
    ->name('inquiries.store');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function (): void {
    Route::get('/', DashboardController::class)->name('index');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::resource('projects', ProjectController::class)->except('show');
    Route::resource('team-members', TeamMemberController::class)->except('show');
});
