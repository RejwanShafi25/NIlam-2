<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Landing page for unauthenticated users
Route::get('/', function () {
    // If user is authenticated, redirect to dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return Inertia::render('Welcome');
})->name('home');

// All auction platform routes require authentication
Route::middleware(['auth', 'verified', 'auction.auth'])->group(function () {
    // Main dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    // Auction routes
    Route::prefix('auctions')->name('auctions.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Auctions/Index');
        })->name('index');
        
        Route::get('/create', function () {
            return Inertia::render('Auctions/Create');
        })->name('create');
        
        Route::get('/{auction}', function () {
            return Inertia::render('Auctions/Show');
        })->name('show');
    });
    
    // User account routes (avoiding conflict with settings/profile)
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Account/Show');
        })->name('show');
        
        Route::get('/edit', function () {
            return Inertia::render('Account/Edit');
        })->name('edit');
    });
    
    // Bidding routes
    Route::prefix('bids')->name('bids.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Bids/Index');
        })->name('index');
    });
});

// Redirect any other routes to home if not authenticated
Route::fallback(function () {
    if (!auth()->check()) {
        return redirect()->route('home')->with('message', 'Please login or register to access the auction platform.');
    }
    abort(404);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
