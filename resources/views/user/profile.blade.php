@extends('layouts.app')

@section('title', 'Data Diri Klien')

@php
    $statusVerifikasi = $customer?->status_verifikasi ?? 'menunggu';
    $locked = $statusVerifikasi === 'disetujui';
@endphp

@section('content')
<div class="container py-4" style="max-width: 1100px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1">Data Diri Klien</h2>
            <p class="text-muted mb-0">Lengkapi data berikut sebelum mengajukan pinjaman.</p>
        </div>
        @if($customer)
            <span class="badge bg-primary fs-6">Status: {{ ucfirst($statusVerifikasi) }}</span>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
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
            <form action="{{ route('client.profile.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $customer?->name) }}" required @disabled($locked)>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">No. HP</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer?->phone) }}" required @disabled($locked)>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">NIK</label>
                        <input type="text" name="nik" class="form-control" value="{{ old('nik', $customer?->nik) }}" required @disabled($locked)>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $customer?->tempat_lahir) }}" required @disabled($locked)>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', optional($customer?->tanggal_lahir)->format('Y-m-d')) }}" required @disabled($locked)>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required @disabled($locked)>
                            <option value="">Pilih</option>
                            <option value="Laki-laki" @selected(old('jenis_kelamin', $customer?->jenis_kelamin) === 'Laki-laki')>Laki-laki</option>
                            <option value="Perempuan" @selected(old('jenis_kelamin', $customer?->jenis_kelamin) === 'Perempuan')>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Golongan Darah</label>
                        <select name="golongan_darah" class="form-select" required @disabled($locked)>
                            @foreach (['A', 'B', 'AB', 'O', '-'] as $golDar)
                                <option value="{{ $golDar }}" @selected(old('golongan_darah', $customer?->golongan_darah) === $golDar)>{{ $golDar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat KTP</label>
                        <textarea name="alamat_ktp" class="form-control" rows="2" required @disabled($locked)>{{ old('alamat_ktp', $customer?->alamat_ktp) }}</textarea>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">RT</label>
                        <input type="text" name="rt" class="form-control" value="{{ old('rt', $customer?->rt) }}" required @disabled($locked)>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">RW</label>
                        <input type="text" name="rw" class="form-control" value="{{ old('rw', $customer?->rw) }}" required @disabled($locked)>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kelurahan</label>
                        <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan', $customer?->kelurahan) }}" required @disabled($locked)>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $customer?->kecamatan) }}" required @disabled($locked)>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Agama</label>
                        <select name="agama" class="form-select" required @disabled($locked)>
                            @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'] as $agama)
                                <option value="{{ $agama }}" @selected(old('agama', $customer?->agama) === $agama)>{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status Perkawinan</label>
                        <select name="status_perkawinan" class="form-select" required @disabled($locked)>
                            @foreach (['Menikah', 'Belum Menikah'] as $status)
                                <option value="{{ $status }}" @selected(old('status_perkawinan', $customer?->status_perkawinan) === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" value="{{ old('pekerjaan', $customer?->pekerjaan) }}" required @disabled($locked)>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kewarganegaraan</label>
                        <select name="kewarganegaraan" class="form-select" required @disabled($locked)>
                            @foreach (['WNI', 'WNA'] as $wn)
                                <option value="{{ $wn }}" @selected(old('kewarganegaraan', $customer?->kewarganegaraan) === $wn)>{{ $wn }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Catatan Tambahan</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Opsional" @disabled($locked)>{{ old('note', $customer?->note) }}</textarea>
                    </div>
                </div>

                @if(!$locked)
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-light border">Kembali</a>
                        <button type="submit" class="btn btn-success px-4">Simpan & Lanjut Data Keuangan</button>
                    </div>
                @else
                    <div class="alert alert-info mt-4 mb-0">
                        Data sudah disetujui admin. Hubungi admin jika ingin mengubah data diri.
                    </div>
                @endif
            </form>
        </div>
    </div>

    @if($customer)
        <div class="alert alert-info mt-3 d-flex justify-content-between align-items-center">
            <span>Data dasar tersimpan. Lanjutkan ke halaman data keuangan.</span>
            <a href="{{ route('client.profile.finance') }}" class="btn btn-outline-primary btn-sm">Isi Data Keuangan</a>
        </div>
    @endif
</div>
@endsection
