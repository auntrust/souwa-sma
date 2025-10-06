<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SeminarCustomerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/entry', [SeminarCustomerController::class, 'store'])->name('seminar_customers.store');
Route::get('/entry/{sid}/{uid?}', [SeminarCustomerController::class, 'entry'])->name('seminar_customers.entry');
Route::get('/entry_finish', function () {
    return Inertia::render('EntryFinish');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('seminars', SeminarController::class);
    Route::resource('customers', CustomerController::class);

    Route::get('/seminars/entry_list/{seminar}', [SeminarController::class, 'entryList'])->name('seminars.entry_list');
});

require __DIR__ . '/auth.php';
