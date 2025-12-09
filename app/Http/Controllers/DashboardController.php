<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Debt;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $verifiedPayments = Payment::where('is_verified', true)->sum('amount');
        return view('dashboard.index', [
            'totalCustomers'  => Customer::count(),
            'totalDebts'      => Debt::sum('amount'),
            'totalPayments'   => $verifiedPayments,
            'totalRemaining'  => max(0, Debt::sum('amount') - $verifiedPayments),
        ]);
    }
}
