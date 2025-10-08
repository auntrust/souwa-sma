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

Route::post('/entry', [SeminarCustomerController::class, 'entryStore'])->name('seminar_customers.entry_store');
Route::get('/entry/{sid}/{cid?}', [SeminarCustomerController::class, 'entry'])->name('seminar_customers.entry');
Route::get('/entry_finish', function () {
    return Inertia::render('EntryFinish');
});

Route::post('/feedback', [SeminarCustomerController::class, 'feedbackStore'])->name('seminar_customers.feedback_store');
Route::get('/feedback/{sid}/{cid}', [SeminarCustomerController::class, 'feedback'])->name('seminar_customers.feedback');

Route::get('/feedback_h_finish/{sid}/{cid}', [SeminarCustomerController::class, 'feedbackHighFinish'])->name('seminar_customers.feedback_h_finish');

Route::post('/feedback_l_input', [SeminarCustomerController::class, 'feedbackLowInputStore'])->name('seminar_customers.feedback_low_input_store');
Route::get('/feedback_l_input/{sid}/{cid}', [SeminarCustomerController::class, 'feedbackLowInput'])->name('seminar_customers.feedback_l_input');
Route::get('/feedback_l_finish/{sid}/{cid}', [SeminarCustomerController::class, 'feedbackLowFinish'])->name('seminar_customers.feedback_l_finish');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('seminars', SeminarController::class);
    Route::resource('customers', CustomerController::class);
    Route::delete('/seminar_customers/{seminarCustomer}', [SeminarCustomerController::class, 'destroy'])->name('seminar_customers.destroy');
    Route::get('/seminars/entry_list/{seminar}', [SeminarController::class, 'entryList'])->name('seminars.entry_list');
});

require __DIR__ . '/auth.php';
