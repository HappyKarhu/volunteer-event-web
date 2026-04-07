<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookmarkController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/pages', [HomeController::class, 'index'])->name('pages.index');
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// User routes
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
    
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    });

Route::middleware(['auth', 'role:organizer'])->group(function () {
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
});

Route::middleware(['auth', 'role:organizer'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/events/saved', [BookmarkController::class, 'index'])->name('events.saved');
    Route::post('/events/{event}/save', [BookmarkController::class, 'store'])->name('events.save');
    Route::delete('/events/{event}/save', [BookmarkController::class, 'destroy'])->name('events.unsave');
});

Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::middleware(['auth', 'role:volunteer'])->post('/events/{event}/apply', [EventController::class, 'apply'])->name('events.apply');
require __DIR__.'/auth.php';
