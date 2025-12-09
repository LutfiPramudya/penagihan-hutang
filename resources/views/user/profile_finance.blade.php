@extends('layouts.app')

@section('title', 'Data Keuangan')

@section('content')
@php
    $locked = ($customer?->status_verifikasi ?? 'menunggu') === 'disetujui';
@endphp
<div class="container py-4" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1">Data Keuangan</h2>
            <p class="text-muted mb-0">Lengkapi informasi finansial untuk pengajuan pinjaman.</p>
        </div>
        <a href="{{ route('client.profile') }}" class="btn btn-light border">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
            <form action="{{ route('client.profile.finance.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                    <input type="text" name="pendidikan_terakhir" class="form-control" value="{{ old('pendidikan_terakhir', $customer?->pendidikan_terakhir) }}" required @disabled($locked)>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Gaji Per Bulan (Rp)</label>
                    <input type="number" name="gaji_per_bulan" min="0" step="0.01" class="form-control" value="{{ old('gaji_per_bulan', $customer?->gaji_per_bulan) }}" required @disabled($locked)>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Bank</label>
                    <input type="text" name="nama_bank" class="form-control" value="{{ old('nama_bank', $customer?->nama_bank) }}" required @disabled($locked)>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Pemilik Rekening</label>
                    <input type="text" name="pemilik_rekening" class="form-control" value="{{ old('pemilik_rekening', $customer?->pemilik_rekening) }}" required @disabled($locked)>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor Rekening</label>
                    <input type="text" name="nomor_rekening" class="form-control" value="{{ old('nomor_rekening', $customer?->nomor_rekening) }}" required @disabled($locked)>
                </div>

                @if(!$locked)
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('client.profile') }}" class="btn btn-light border">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Data Keuangan</button>
                    </div>
                @else
                    <div class="alert alert-info mt-4 mb-0">
                        Data sudah disetujui admin. Hubungi admin jika ingin mengubah data keuangan.
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="alert alert-info mt-3">
        Setelah tersimpan, Anda bisa melanjutkan ke <a href="{{ route('client.loans.index') }}" class="fw-semibold">Peminjaman Saya</a>.
    </div>
</div>
@endsection
