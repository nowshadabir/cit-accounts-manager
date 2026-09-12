<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/offline', function () {
    return response()->file(public_path('offline.html'));
})->name('offline');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User Profile & Password Reset via OTP
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password/send-otp', [ProfileController::class, 'sendPasswordOtp'])->name('profile.password.send-otp');
    Route::post('/profile/password/update', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Financial Entry Form & Transactions (Read for all authenticated)
    Route::get('/entry-form', [EntryController::class, 'index'])->name('entries.index');
    Route::get('/documents/{type}/{id}', [EntryController::class, 'downloadDocument'])->name('documents.download');

    // Financial Reports & Excel Export
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportExcel'])->name('reports.export');

    // Financial Entry Mutations (Restricted to Edit / Super Admin access)
    Route::middleware('can_edit_accounts')->group(function () {
        Route::post('/entry-form/deposit', [EntryController::class, 'storeDeposit'])->name('entries.deposit.store');
        Route::post('/entry-form/disbursal', [EntryController::class, 'storeDisbursal'])->name('entries.disbursal.store');
        Route::post('/entry-form/expense', [EntryController::class, 'storeExpense'])->name('entries.expense.store');
        Route::delete('/entry-form/deposit/{deposit}', [EntryController::class, 'destroyDeposit'])->name('entries.deposit.destroy');
        Route::delete('/entry-form/disbursal/{disbursal}', [EntryController::class, 'destroyDisbursal'])->name('entries.disbursal.destroy');
        Route::delete('/entry-form/expense/{expense}', [EntryController::class, 'destroyExpense'])->name('entries.expense.destroy');
    });

    // Users Management Routes (Super Admin only)
    Route::middleware('super_admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Settings Routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/mail', [SettingsController::class, 'updateMail'])->name('settings.mail.update');
    Route::post('/settings/mail/test', [SettingsController::class, 'testMail'])->name('settings.mail.test');
    Route::post('/settings/ftp', [SettingsController::class, 'updateFtp'])->name('settings.ftp.update');
    Route::post('/settings/ftp/test', [SettingsController::class, 'testFtp'])->name('settings.ftp.test');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
