<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\UnifiedLoginController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Middleware\EnsureRole;
use App\Http\Controllers\AdminLoanController;
use App\Http\Controllers\ClientPaymentController;


// ===========================
// REDIRECT ROOT
// ===========================
Route::get('/', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('admin.login');
    }

    return $user->role === 'admin'
        ? redirect()->route('dashboard')
        : redirect()->route('client.loans.index');
});


// ===========================
// ADMIN AUTH (PAKAI AdminAuthController)
// ===========================
Route::prefix('admin')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLogin'])
        ->middleware('guest')
        ->name('admin.login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('guest')
        ->name('admin.login.submit');

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('admin.logout');
});


// ===========================
// ADMIN AREA
// ===========================
Route::middleware(['auth', EnsureRole::class . ':admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::resource('customers', CustomerController::class);

    // Nested hutang & pembayaran
    Route::prefix('customers/{customer}')->group(function () {

        // Hutang
        Route::get('debts', [DebtController::class, 'index'])->name('debts.index');
        Route::get('debts/create', [DebtController::class, 'create'])->name('debts.create');
        Route::post('debts', [DebtController::class, 'store'])->name('debts.store');
        Route::get('debts/{debt}/edit', [DebtController::class, 'edit'])->name('debts.edit');
        Route::put('debts/{debt}', [DebtController::class, 'update'])->name('debts.update');
        Route::delete('debts/{debt}', [DebtController::class, 'destroy'])->name('debts.destroy');

        // Pembayaran hutang
        Route::get('debts/{debt}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('debts/{debt}/payments', [PaymentController::class, 'store'])->name('payments.store');
    });

    // Pinjaman khusus admin
    Route::prefix('admin/loans')->name('admin.loans.')->group(function () {
        Route::get('/', [AdminLoanController::class, 'index'])->name('index');
        Route::post('{debt}/approve', [AdminLoanController::class, 'approve'])->name('approve');
        Route::post('{debt}/reject', [AdminLoanController::class, 'reject'])->name('reject');
        Route::get('{debt}/payment', [AdminLoanController::class, 'createPayment'])->name('payment.create');
        Route::post('{debt}/payment', [AdminLoanController::class, 'storePayment'])->name('payment.store');
        Route::get('payments/verify', [AdminLoanController::class, 'paymentsToVerify'])->name('payments.verify');
        Route::post('payments/{payment}/verify', [AdminLoanController::class, 'verifyPayment'])->name('payments.verify.submit');
    });
});


// ===========================
// CLIENT AUTH
// ===========================
Route::prefix('client')->name('client.')->group(function () {

    // Registrasi client
    Route::get('register', [ClientAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [ClientAuthController::class, 'register'])->name('register.submit');

    // Login client (gunakan unified login)
    Route::get('login', [UnifiedLoginController::class, 'showLogin'])->name('login');
    Route::post('login', [UnifiedLoginController::class, 'login'])->name('login.submit');

    Route::post('logout', [UnifiedLoginController::class, 'logout'])->name('logout');

    // ===========================
    // CLIENT AREA
    // ===========================
    Route::middleware(['auth', EnsureRole::class . ':customer'])->group(function () {

        Route::get('data-diri', [ClientController::class, 'profile'])->name('profile');
        Route::post('data-diri', [ClientController::class, 'storeProfile'])->name('profile.store');

        Route::get('data-diri/keuangan', [ClientController::class, 'finance'])->name('profile.finance');
        Route::post('data-diri/keuangan', [ClientController::class, 'storeFinance'])->name('profile.finance.store');

        Route::get('peminjaman', [ClientController::class, 'loans'])->name('loans.index');
        Route::get('peminjaman/create', [ClientController::class, 'createLoan'])->name('loans.create');
        Route::post('peminjaman', [ClientController::class, 'storeLoan'])->name('loans.store');
        Route::get('peminjaman/{debt}', [ClientController::class, 'showLoan'])->name('loans.show');

        Route::get('peminjaman/{debt}/bayar', [ClientPaymentController::class, 'create'])->name('loans.pay');
        Route::post('peminjaman/{debt}/bayar', [ClientPaymentController::class, 'store'])->name('loans.pay.store');

        Route::get('riwayat-pembayaran', [ClientController::class, 'paymentHistory'])->name('payments');
    });
});
