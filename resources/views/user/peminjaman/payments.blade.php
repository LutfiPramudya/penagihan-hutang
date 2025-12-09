@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1">Riwayat Pembayaran</h2>
            <p class="text-muted mb-0">Semua pembayaran yang sudah tercatat.</p>
        </div>
        <a href="{{ route('client.loans.index') }}" class="btn btn-light border">Kembali</a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Pinjaman</th>
                        <th>Nominal Bayar</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td>{{ \Illuminate\Support\Carbon::parse($payment->date)->format('d M Y') }}</td>
                            <td>
                                Rp {{ number_format($payment->debt->amount, 0, ',', '.') }}<br>
                                <small class="text-muted">Status: {{ ucfirst($payment->debt->status) }}</small>
                            </td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada pembayaran yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
