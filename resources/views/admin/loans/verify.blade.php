@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Verifikasi Pembayaran</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Debitur</th>
                            <th>Pinjaman</th>
                            <th>Nominal Bayar</th>
                            <th>Tanggal</th>
                            <th>Catatan</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payment->debt->customer->name ?? '-' }}</td>
                                <td>Rp {{ number_format($payment->debt->amount,0,',','.') }}</td>
                                <td>Rp {{ number_format($payment->amount,0,',','.') }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($payment->date)->format('d M Y') }}</td>
                                <td>{{ $payment->note ?? '-' }}</td>
                                <td>
                                    @if($payment->proof_path)
                                        <a href="{{ asset('storage/'.$payment->proof_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.loans.payments.verify.submit', $payment) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Verifikasi</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada pembayaran pending verifikasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
