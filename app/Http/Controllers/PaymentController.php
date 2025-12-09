<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Customer $customer, Debt $debt)
    {
        return view('payments.create', compact('customer', 'debt'));
    }

    public function store(Request $request, Customer $customer, Debt $debt)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'date'   => ['required', 'date'],
            'note'   => ['nullable', 'string'],
        ]);

        $data['debt_id']    = $debt->id;
        $data['is_verified'] = true;
        $data['verified_at'] = now();
        $data['recorded_by'] = 'admin';

        Payment::create($data);

        $totalPaid   = $debt->payments()->where('is_verified', true)->sum('amount');
        $totalTarget = $debt->total_pengembalian ?: $debt->amount;
        $debt->update([
            'status' => $totalPaid >= $totalTarget ? 'lunas' : 'belum lunas',
        ]);

        return redirect()
            ->route('customers.show', $customer->id)
            ->with('success', 'Pembayaran berhasil ditambahkan dan terverifikasi!');
    }
}
