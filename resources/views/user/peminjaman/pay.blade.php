@extends('layouts.app')

@section('title', 'Kirim Bukti Pembayaran')

@section('content')
<div class="container py-4" style="max-width: 720px;">
    <h2 class="fw-bold mb-3">Kirim Bukti Pembayaran</h2>
    <p class="text-muted mb-3">Debitur: <strong>{{ $customer->name }}</strong> | Pinjaman: Rp {{ number_format($debt->amount,0,',','.') }}</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <form action="{{ route('client.loans.pay.store', $debt->id) }}" method="POST" enctype="multipart/form-data">
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
                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload Bukti Pembayaran (jpg/png, max 2MB)</label>
                    <input type="file" name="proof" class="form-control" accept="image/*" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('client.loans.show', $debt->id) }}" class="btn btn-light border">Kembali</a>
                    <button class="btn btn-primary">Kirim Bukti</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
