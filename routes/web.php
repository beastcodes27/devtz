<?php

use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', PortfolioController::class)->name('portfolio');

Route::post('/inquiries', InquiryController::class)
    ->middleware('throttle:6,1')
    ->name('inquiries.store');
