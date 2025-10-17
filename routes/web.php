<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmailLogController;
use App\Http\Controllers\SeminarCustomerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // 認証済みの場合はセミナー一覧にリダイレクト
    if (auth()->check()) {
        return redirect()->route('seminars.index');
    }

    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
    // 未認証の場合は404エラーを表示
    abort(404);
});

Route::post('/entry', [SeminarCustomerController::class, 'entryStore'])->name('seminar_customers.entry_store');
Route::get('/entry/{sid}/{cid?}', [SeminarCustomerController::class, 'entry'])->name('seminar_customers.entry');
Route::get('/entry_finish', function () {
    return Inertia::render('EntryFinish');
});

Route::post('/feedback', [SeminarCustomerController::class, 'feedbackStore'])->name('seminar_customers.feedback_store');
Route::get('/feedback/{sid}/{cid}', [SeminarCustomerController::class, 'feedback'])->name('seminar_customers.feedback');

Route::post('/resubscribe/{cid}', [CustomerController::class, 'resubscribe'])->name('customers.resubscribe');
Route::get('/unsubscribe/{cid}', [CustomerController::class, 'unsubscribe'])->name('customers.unsubscribe');

Route::get('/feedback_h_finish/{sid}/{cid}', [SeminarCustomerController::class, 'feedbackHighFinish'])->name('seminar_customers.feedback_h_finish');

Route::post('/feedback_l_input', [SeminarCustomerController::class, 'feedbackLowInputStore'])->name('seminar_customers.feedback_low_input_store');
Route::get('/feedback_l_input/{sid}/{cid}', [SeminarCustomerController::class, 'feedbackLowInput'])->name('seminar_customers.feedback_l_input');
Route::get('/feedback_l_finish/{sid}/{cid}', [SeminarCustomerController::class, 'feedbackLowFinish'])->name('seminar_customers.feedback_l_finish');

Route::get('/seminar_customers/show_feedback/{sid}/{cid}', [SeminarCustomerController::class, 'showFeedback'])->name('seminar_customers.show_feedback');

Route::get('/webinar/{sid}/{cid}', [SeminarCustomerController::class, 'Webinar'])->name('seminar_customers.webinar');
Route::get('/to_webinar/{sid}/{cid}', [SeminarCustomerController::class, 'toWebinar'])->name('seminar_customers.to_webinar');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('seminars', SeminarController::class);
    Route::resource('customers', CustomerController::class);
    Route::delete('/seminar_customers/{seminarCustomer}', [SeminarCustomerController::class, 'destroy'])->name('seminar_customers.destroy');
    Route::get('/seminars/entry_list/{seminar}', [SeminarController::class, 'entryList'])->name('seminars.entry_list');

    Route::resource('email-logs', EmailLogController::class)->only(['index', 'show']);
    // Route::post('email-logs/{emailLog}/resend', [EmailLogController::class, 'resend'])->name('email-logs.resend');
});

require __DIR__ . '/auth.php';
