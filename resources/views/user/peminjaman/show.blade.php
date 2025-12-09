@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="container py-4" style="max-width: 1000px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1">Detail Peminjaman</h2>
            <p class="text-muted mb-0">Rincian pengajuan dan pembayaran.</p>
        </div>
        <a href="{{ route('client.loans.index') }}" class="btn btn-light border">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
        $userPayments  = $debt->payments->where('recorded_by', 'customer');
        $totalBayarUser = $userPayments->sum('amount');
        $sisa = max(0, ($debt->total_pengembalian ?: $debt->amount) - $totalBayarUser);
    @endphp

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="text-muted small">Nominal</div>
                            <div class="fs-4 fw-semibold">Rp {{ number_format($debt->amount, 0, ',', '.') }}</div>
                        </div>
                        <span class="badge {{ $debt->status === 'lunas' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst($debt->status) }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Tenor</span>
                        <div class="fw-semibold">{{ $debt->tenor_bulan ?? '-' }} bulan</div>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Bunga</span>
                        <div class="fw-semibold">{{ $debt->bunga_persen ?? 0 }}%</div>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Total Pengembalian</span>
                        <div class="fw-semibold">Rp {{ number_format($debt->total_pengembalian ?? $debt->amount, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Cicilan / bulan</span>
                        <div class="fw-semibold">Rp {{ number_format($debt->cicilan_per_bulan ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Tanggal Pengajuan</span>
                        <div class="fw-semibold">{{ optional($debt->tanggal_pengajuan)->format('d M Y') ?? '-' }}</div>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Jatuh Tempo</span>
                        <div class="fw-semibold">{{ optional($debt->tanggal_jatuh_tempo)->format('d M Y') ?? '-' }}</div>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Catatan</span>
                        <div class="fw-semibold">{{ $debt->note ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body">
                    <div class="mb-2">
                        <div class="text-muted small">Total Dibayar</div>
                        <div class="fs-4 fw-semibold text-success">Rp {{ number_format($totalBayarUser, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-2">
                        <div class="text-muted small">Sisa Tagihan</div>
                        <div class="fs-4 fw-semibold text-danger">Rp {{ number_format($sisa, 0, ',', '.') }}</div>
                    </div>
                    <div class="alert alert-info mt-3">
                        Untuk melakukan pembayaran, hubungi admin. Riwayat pembayaran tercatat di bawah.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mt-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="fw-semibold mb-0">Riwayat Pembayaran</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('client.payments') }}" class="btn btn-outline-secondary btn-sm">Lihat Semua</a>
                    @if($debt->status !== 'lunas')
                        <a href="{{ route('client.loans.pay', $debt->id) }}" class="btn btn-primary btn-sm">Kirim Bukti Pembayaran</a>
                    @endif
                </div>
            </div>
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Catatan</th>
                        <th>Sumber</th>
                        <th>Bukti</th>
                        <th>Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($userPayments as $payment)
                        <tr>
                            <td>{{ \Illuminate\Support\Carbon::parse($payment->date)->format('d M Y') }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->note ?? '-' }}</td>
                            <td>
                                <span class="badge {{ ($payment->recorded_by ?? 'customer') === 'admin' ? 'bg-secondary' : 'bg-info text-dark' }}">
                                    {{ ($payment->recorded_by ?? 'customer') === 'admin' ? 'Admin' : 'User' }}
                                </span>
                            </td>
                            <td>
                                @if($payment->proof_path)
                                    <a href="{{ asset('storage/'.$payment->proof_path) }}" target="_blank">Lihat</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $payment->is_verified ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $payment->is_verified ? 'Terverifikasi' : 'Menunggu' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
