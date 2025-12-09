<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminLoanController extends Controller
{
    public function index()
    {
        $loans = Debt::with('customer')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.loans.index', compact('loans'));
    }

    public function approve(Debt $debt, Request $request)
    {
        $debt->update([
            'approval_status' => 'approved',
            'approved_at'     => now(),
            'rejected_reason' => null,
            'status'          => 'belum lunas',
        ]);

        return back()->with('success', 'Pinjaman disetujui.');
    }

    public function reject(Debt $debt, Request $request)
    {
        $data = $request->validate([
            'reason' => ['nullable', 'string'],
        ]);

        $debt->update([
            'approval_status' => 'rejected',
            'approved_at'     => null,
            'rejected_reason' => $data['reason'] ?? 'Ditolak admin',
            'status'          => 'belum lunas',
        ]);

        return back()->with('success', 'Pinjaman ditolak.');
    }

    public function createPayment(Debt $debt)
    {
        $debt->load('customer');
        return view('admin.loans.payment', compact('debt'));
    }

    public function storePayment(Request $request, Debt $debt)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'date'   => ['required', 'date'],
            'note'   => ['nullable', 'string'],
        ]);

        $data['debt_id']     = $debt->id;
        $data['is_verified'] = true;
        $data['verified_at'] = now();
        $data['recorded_by'] = 'admin';

        Payment::create($data);

        $totalPaid   = $debt->payments()->where('is_verified', true)->sum('amount');
        $totalTarget = $debt->total_pengembalian ?: $debt->amount;
        if ($totalPaid >= $totalTarget) {
            $debt->update(['status' => 'lunas']);
        } else {
            $debt->update(['status' => 'belum lunas']);
        }

        return redirect()->route('admin.loans.index')->with('success', 'Pembayaran dicatat & terverifikasi.');
    }

    public function paymentsToVerify()
    {
        $payments = Payment::with('debt.customer')
            ->where('is_verified', false)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.loans.verify', compact('payments'));
    }

    public function verifyPayment(Payment $payment)
    {
        $payment->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        // auto-lunas jika total bayar >= amount
        $debt = $payment->debt;
        $totalPaid   = $debt->payments()->where('is_verified', true)->sum('amount');
        $totalTarget = $debt->total_pengembalian ?: $debt->amount;
        if ($totalPaid >= $totalTarget) {
            $debt->update(['status' => 'lunas']);
        } else {
            $debt->update(['status' => 'belum lunas']);
        }

        return back()->with('success', 'Pembayaran diverifikasi.');
    }
}
