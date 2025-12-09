@extends('layouts.app')

@section('title', 'Input Pembayaran')

@section('content')
<div class="container py-4" style="max-width: 720px;">
    <h2 class="fw-bold mb-3">Pencatatan Pembayaran Admin</h2>
    <p class="text-muted mb-3">Debitur: <strong>{{ $debt->customer->name ?? '-' }}</strong> | Pinjaman: Rp {{ number_format($debt->amount,0,',','.') }}</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Form Pencatatan Pembayaran</h5>
            <form action="{{ route('admin.loans.payment.store', $debt) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nominal Bayar (Rp)</label>
                    <input type="number" name="amount" min="1" class="form-control" value="{{ old('amount') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Bayar</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', now()->toDateString()) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="note" class="form-control" rows="2" placeholder="Opsional">{{ old('note') }}</textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.loans.index') }}" class="btn btn-light border">Kembali</a>
                    <button class="btn btn-primary">Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
