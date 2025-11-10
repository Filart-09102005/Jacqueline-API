<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HolidaySystemController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

// ===========================
// PUBLIC ROUTES
// ===========================
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

// Reset Password
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->name('password.update');
// ===========================
// EMAIL VERIFICATION
// ===========================
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        abort(403, 'Invalid verification link.');
    }

    if (is_null($user->email_verified_at)) {
        $user->forceFill([
            'email_verified_at' => Carbon::now(),
        ])->save();
    }

    return view('auth.verification-success', ['user' => $user]);
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    $user = $request->user();

    if ($user->hasVerifiedEmail()) {
        return back()->with('success', 'Email is already verified.');
    }

    $user->sendEmailVerificationNotification();
    return back()->with('success', 'A new verification link has been sent to your email.');
})->middleware('auth')->name('verification.send');

// ===========================
// PROTECTED ROUTES
// ===========================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HolidaySystemController::class, 'dashboard'])->name('dashboard');
    Route::get('/holidays', [HolidaySystemController::class, 'holidays'])->name('holidays');
    Route::get('/countries', [HolidaySystemController::class, 'countries'])->name('countries');
    Route::get('/compare', [HolidaySystemController::class, 'compare'])->name('compare');
    Route::get('/statistics', [HolidaySystemController::class, 'statistics'])->name('statistics');
});

// ===========================
// LOGOUT
// ===========================
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
