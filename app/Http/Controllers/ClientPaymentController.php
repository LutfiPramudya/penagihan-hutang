<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientPaymentController extends Controller
{
    private function currentCustomer(Request $request): ?Customer
    {
        return $request->user()?->customer;
    }

    private function ensureDebtOwner(Debt $debt, Customer $customer)
    {
        abort_if($debt->customer_id !== $customer->id, 403, 'Akses ditolak.');
    }

    public function create(Request $request, Debt $debt)
    {
        $customer = $this->currentCustomer($request);
        if (!$customer) {
            return redirect()->route('login');
        }
        $this->ensureDebtOwner($debt, $customer);

        return view('user.peminjaman.pay', compact('customer', 'debt'));
    }

    public function store(Request $request, Debt $debt)
    {
        $customer = $this->currentCustomer($request);
        if (!$customer) {
            return redirect()->route('login');
        }
        $this->ensureDebtOwner($debt, $customer);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'date'   => ['required', 'date'],
            'note'   => ['nullable', 'string'],
            'proof'  => ['required', 'file', 'image', 'max:2048'],
        ]);

        $path = $request->file('proof')->store('bukti', 'public');

        Payment::create([
            'debt_id'    => $debt->id,
            'amount'     => $data['amount'],
            'date'       => $data['date'],
            'note'       => $data['note'] ?? null,
            'proof_path' => $path,
            'is_verified'=> false,
            'recorded_by'=> 'customer',
        ]);

        return redirect()
            ->route('client.loans.show', $debt->id)
            ->with('success', 'Bukti pembayaran dikirim, menunggu verifikasi admin.');
    }
}
