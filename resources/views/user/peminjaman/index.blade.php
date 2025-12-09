@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="container py-4" style="max-width: 1100px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1">Peminjaman Saya</h2>
            <p class="text-muted mb-0">Ajukan pinjaman baru atau lihat status pinjaman yang berjalan.</p>
        </div>
        @if(($customer->status_verifikasi ?? 'menunggu') === 'disetujui')
            <a href="{{ route('client.loans.create') }}" class="btn btn-primary">Ajukan Peminjaman</a>
        @else
            <span class="badge bg-warning text-dark">Menunggu persetujuan admin</span>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body d-flex flex-wrap gap-3">
            <div>
                <div class="text-muted small">Status Verifikasi</div>
                <div class="fs-5 fw-semibold">{{ ucfirst($customer->status_verifikasi ?? 'menunggu') }}</div>
            </div>
            <div>
                <div class="text-muted small">Limit Pinjaman</div>
                <div class="fs-5 fw-semibold">Rp {{ number_format($customer->limit_pinjaman ?? 0, 0, ',', '.') }}</div>
            </div>
            <div>
                <div class="text-muted small">Total Pinjaman Aktif</div>
                <div class="fs-5 fw-semibold">
                    Rp {{ number_format($debts->where('status', '!=', 'lunas')->sum('amount'), 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nominal</th>
                        <th>Tenor (bulan)</th>
                        <th>Bunga (%)</th>
                        <th>Status</th>
                        <th>Total Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($debts as $debt)
                        @php
                            $totalPaid = $debt->payments
                                ->where('is_verified', true)
                                ->where('recorded_by', 'customer')
                                ->sum('amount');
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>Rp {{ number_format($debt->amount, 0, ',', '.') }}</td>
                            <td>{{ $debt->tenor_bulan ?? '-' }}</td>
                            <td>{{ $debt->bunga_persen ?? 0 }}%</td>
                            <td>
                                <span class="badge {{ $debt->status === 'lunas' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst($debt->status) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('client.loans.show', $debt->id) }}" class="btn btn-outline-primary btn-sm">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada pengajuan. Klik "Ajukan Peminjaman" untuk mulai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
