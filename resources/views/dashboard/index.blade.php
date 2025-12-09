@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4 text-center">Dashboard Admin</h2>

    <div class="row g-3">
        <div class="col-md-3 col-6">
            <div class="card shadow-sm text-center p-3 rounded-4 border-0">
                <div class="text-muted small">Total Debitur</div>
                <div class="display-6 fw-bold text-primary">{{ $totalCustomers }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm text-center p-3 rounded-4 border-0">
                <div class="text-muted small">Total Hutang Masuk</div>
                <div class="display-6 fw-bold text-danger">Rp {{ number_format($totalDebts,0,',','.') }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm text-center p-3 rounded-4 border-0">
                <div class="text-muted small">Total Pembayaran</div>
                <div class="display-6 fw-bold text-success">Rp {{ number_format($totalPayments,0,',','.') }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card shadow-sm text-center p-3 rounded-4 border-0">
                <div class="text-muted small">Sisa Hutang</div>
                <div class="display-6 fw-bold text-warning">Rp {{ number_format($totalRemaining,0,',','.') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
