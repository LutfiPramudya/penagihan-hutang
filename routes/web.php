<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\UnifiedLoginController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Middleware\EnsureRole;
use App\Http\Controllers\AdminLoanController;
use App\Http\Controllers\ClientPaymentController;

Route::get('/', function () {
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }
    return $user->role === 'admin'
        ? redirect()->route('dashboard')
        : redirect()->route('client.loans.index');
});

// ADMIN AUTH
Route::get('login', [UnifiedLoginController::class, 'showLogin'])
    ->middleware(['guest'])
    ->name('login');
Route::post('login', [UnifiedLoginController::class, 'login'])
    ->middleware(['guest'])
    ->name('login.submit');
Route::post('logout', [UnifiedLoginController::class, 'logout'])->name('logout');
Route::get('logout', [UnifiedLoginController::class, 'logout'])->middleware('auth')->name('logout.get'); // fallback jika logout diakses via GET
// kompatibilitas lama
Route::get('admin/login', fn () => redirect()->route('login'))->name('admin.login');
Route::post('admin/login', [UnifiedLoginController::class, 'login'])
    ->name('admin.login.submit')
    ->middleware(['guest:web', 'guest:client']);
Route::post('admin/logout', [UnifiedLoginController::class, 'logout'])->name('admin.logout');

// ADMIN AREA (auth:web)
Route::middleware(['auth', EnsureRole::class . ':admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::resource('customers', CustomerController::class);

    // Nested Hutang & Pembayaran
    Route::prefix('customers/{customer}')->group(function () {

        // Hutang
        Route::get('debts', [DebtController::class, 'index'])->name('debts.index');
        Route::get('debts/create', [DebtController::class, 'create'])->name('debts.create');
        Route::post('debts', [DebtController::class, 'store'])->name('debts.store');
        Route::get('debts/{debt}/edit', [DebtController::class, 'edit'])->name('debts.edit');
        Route::put('debts/{debt}', [DebtController::class, 'update'])->name('debts.update');
        Route::delete('debts/{debt}', [DebtController::class, 'destroy'])->name('debts.destroy');

        // Pembayaran
        Route::get('debts/{debt}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('debts/{debt}/payments', [PaymentController::class, 'store'])->name('payments.store');
    });

    // Kelola Pinjaman (admin)
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

// Client area (tanpa autentikasi, pakai session customer)
Route::prefix('client')->name('client.')->group(function () {
    Route::get('register', [ClientAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [ClientAuthController::class, 'register'])->name('register.submit');
    Route::get('login', fn () => redirect()->route('login'))->name('login');
    Route::post('login', [UnifiedLoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [UnifiedLoginController::class, 'logout'])->name('logout');

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
