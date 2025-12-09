<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('debts')->orderBy('name')->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'note'    => ['nullable', 'string'],
        ]);

        Customer::create($data);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Debitur berhasil ditambahkan.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['debts.payments', 'payments']);

        $totalHutang = $customer->debts->sum('amount');
        // gunakan nama tabel untuk menghindari kolom ambigu
        $totalPembayaran = $customer->payments()
            ->where('payments.is_verified', true)
            ->sum('payments.amount');
        $sisaHutang = max(0, $totalHutang - $totalPembayaran);

        return view('customers.show', compact(
            'customer',
            'totalHutang',
            'totalPembayaran',
            'sisaHutang'
        ));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'phone'             => ['required', 'string', 'max:50'],
            'address'           => ['nullable', 'string'],
            'note'              => ['nullable', 'string'],
            'status_verifikasi' => ['nullable', 'in:menunggu,disetujui,ditolak'],
            'limit_pinjaman'    => ['nullable', 'numeric', 'min:0'],
            'alasan_penolakan'  => ['nullable', 'string'],
        ]);

        if (($data['status_verifikasi'] ?? $customer->status_verifikasi) === 'disetujui') {
            $data['disetujui_pada'] = now();
            $data['alasan_penolakan'] = null;
        }

        $customer->update($data);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Data debitur berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Debitur berhasil dihapus.');
    }
}
