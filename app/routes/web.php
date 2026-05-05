<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Organizer\EventController as OrganizerEventController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/event/{event}/calendar', [EventController::class, 'calendar'])->name('events.calendar');

// Authenticated User Routes (Base)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User registrations
    Route::post('/event/{event}/register', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/my-registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::delete('/registration/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');
    
    // Breeze default dashboard redirect based on role
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'organizer' => redirect()->route('organizer.dashboard'),
            default     => redirect()->route('home'),
        };
    })->name('dashboard');
});

// Organizer Routes
Route::middleware(['auth', 'isOrganizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard', [OrganizerEventController::class, 'dashboard'])->name('dashboard');
    Route::get('/event/create', [OrganizerEventController::class, 'create'])->name('events.create');
    Route::post('/event/store', [OrganizerEventController::class, 'store'])->name('events.store');
    
    // Event editing
    Route::get('/event/{event}/edit', [OrganizerEventController::class, 'edit'])->name('events.edit');
    Route::put('/event/{event}/update', [OrganizerEventController::class, 'update'])->name('events.update');
    
    // Attendance routes
    Route::get('/event/{event}/attendees', [OrganizerEventController::class, 'attendees'])->name('events.attendees');
    Route::get('/event/{event}/export', [OrganizerEventController::class, 'export'])->name('events.export');
    Route::post('/registration/{registration}/toggle-attendance', [OrganizerEventController::class, 'toggleAttendance'])->name('registrations.toggle_attendance');
});

// Admin Routes
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminEventController::class, 'dashboard'])->name('dashboard');
    Route::get('/event/{event}', [AdminEventController::class, 'show'])->name('events.show');
    Route::post('/event/{event}/approve', [AdminEventController::class, 'approve'])->name('events.approve');
    Route::post('/event/{event}/reject', [AdminEventController::class, 'reject'])->name('events.reject');
});

require __DIR__.'/auth.php';
