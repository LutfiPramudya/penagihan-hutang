@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
<div class="container py-4" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1">Ajukan Peminjaman</h2>
            <p class="text-muted mb-0">Isi nominal yang ingin diajukan.</p>
        </div>
        <a href="{{ route('client.loans.index') }}" class="btn btn-light border">Kembali</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-2">Perbaiki data berikut:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('client.loans.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nominal Pinjaman (Rp)</label>
                    <input type="number" name="amount" min="1" class="form-control form-control-lg" value="{{ old('amount') }}" required>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tenor (bulan)</label>
                        <select name="tenor_bulan" class="form-select" required>
                            <option value="">Pilih tenor</option>
                            <option value="3" @selected(old('tenor_bulan')==3)>3 bulan (bunga 2%)</option>
                            <option value="6" @selected(old('tenor_bulan')==6)>6 bulan (bunga 5%)</option>
                            <option value="12" @selected(old('tenor_bulan')==12)>12 bulan (bunga 7%)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bunga</label>
                        <input type="text" class="form-control" value="Otomatis sesuai tenor" disabled>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label fw-semibold">Catatan</label>
                    <textarea name="note" class="form-control" rows="2" placeholder="Opsional">{{ old('note') }}</textarea>
                </div>

                <div class="alert alert-info mt-3 mb-0">
                    Data diri atas nama <strong>{{ $customer->name }}</strong> akan dikirim sebagai pemohon.
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
