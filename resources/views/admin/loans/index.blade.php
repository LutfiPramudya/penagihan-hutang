@extends('layouts.app')

@section('title', 'Kelola Pinjaman')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Kelola Pinjaman</h2>

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
                            <th>Nominal</th>
                            <th>Tenor</th>
                            <th>Status Approval</th>
                            <th>Status Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $loan->customer->name ?? '-' }}</td>
                                <td>Rp {{ number_format($loan->amount, 0, ',', '.') }}</td>
                                <td>{{ $loan->tenor_bulan ?? '-' }} bln</td>
                                <td>
                                    @php $appr = $loan->approval_status ?? 'pending'; @endphp
                                    <span class="badge 
                                        @if($appr === 'approved') bg-success 
                                        @elseif($appr === 'rejected') bg-danger 
                                        @else bg-warning text-dark @endif">
                                        {{ ucfirst($appr) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $loan->status === 'lunas' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td class="d-flex gap-2 flex-wrap">
                                    @if(($loan->approval_status ?? 'pending') === 'pending')
                                        <form action="{{ route('admin.loans.approve', $loan) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.loans.reject', $loan) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-danger">Tolak</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.loans.payment.create', $loan) }}" class="btn btn-sm btn-primary">Input Pembayaran</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada pinjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
