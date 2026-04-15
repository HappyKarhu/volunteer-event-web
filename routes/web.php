<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\SectionVolunteerController;
use App\Http\Controllers\EventApplicationController;


//public routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/pages', [HomeController::class, 'index'])->name('pages.index');
Route::get('/events', [EventController::class, 'index'])->name('events.index');

//user public profile
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

//dashboard routes (loggin required)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

//profile routes (logged in users only)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

//bookmark routes (logged in users only)    
Route::middleware('auth')->group(function () {
    Route::get('/events/saved', [BookmarkController::class, 'index'])->name('events.saved');
    Route::post('/events/{event}/save', [BookmarkController::class, 'store'])->name('events.save');
    Route::delete('/events/{event}/save', [BookmarkController::class, 'destroy'])->name('events.unsave');
});

//orginizer routes (logged in organizers only)
Route::middleware(['auth', 'role:organizer'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');

    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
    //application management routes
    Route::get('/events/{event}/applications', [EventApplicationController::class, 'index'])
        ->name('events.applications');
    Route::post('/applications/{application}/approve', [EventApplicationController::class, 'approve'])
        ->name('applications.approve');
    Route::post('/applications/{application}/reject', [EventApplicationController::class, 'reject'])
        ->name('applications.reject');
    Route::delete('/applications/{application}', [EventApplicationController::class, 'destroy'])
        ->name('applications.destroy')
        ->middleware(['auth', 'role:volunteer']);
});

//volunteer routes (logged in volunteers only)
Route::middleware(['auth', 'role:volunteer'])->group(function () {
    Route::post('/events/{event}/apply', [EventApplicationController::class, 'store'])
        ->middleware(['auth', 'role:volunteer'])
        ->name('events.apply');
});

Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::post('/sections/{section}/join', [SectionVolunteerController::class, 'join'])->name('sections.join');

require __DIR__.'/auth.php';
