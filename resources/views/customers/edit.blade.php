@extends('layouts.app')

@section('title', 'Edit Debitur')

@section('content')
<div class="container mt-5" style="max-width: 820px;">

    <h2 class="fw-bold text-center mb-4">Edit / Verifikasi Debitur</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Data Debitur</h5>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Debitur</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $customer->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">No HP</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $customer->phone) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $customer->address) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan</label>
                            <textarea name="note" class="form-control" rows="2">{{ old('note', $customer->note) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Penetapan Limit & Verifikasi</h5>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status Verifikasi</label>
                            <select name="status_verifikasi" class="form-select">
                                @foreach (['menunggu'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak'] as $val=>$label)
                                    <option value="{{ $val }}" @selected(old('status_verifikasi',$customer->status_verifikasi)===$val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Limit Pinjaman (Rp)</label>
                            <input type="number" name="limit_pinjaman" class="form-control" min="0" step="1000" value="{{ old('limit_pinjaman', $customer->limit_pinjaman) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alasan Penolakan</label>
                            <input type="text" name="alasan_penolakan" class="form-control" value="{{ old('alasan_penolakan', $customer->alasan_penolakan) }}" placeholder="Opsional saat ditolak">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>

</div>
@endsection
