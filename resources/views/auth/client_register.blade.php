@extends('layouts.app')

@section('title', 'Registrasi Klien')

@section('content')
<div class="container py-5" style="max-width: 520px;">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-3 text-center">Registrasi Klien</h3>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('client.register.submit') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email (opsional)</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">No. HP (opsional)</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="alert alert-info">
                    Anda bisa registrasi menggunakan email atau nomor HP (salah satu wajib diisi), lalu login dengan kredensial tersebut.
                </div>
                <button class="btn btn-success w-100">Daftar</button>
            </form>

            <div class="text-center mt-3">
                Sudah punya akun? <a href="{{ route('client.login') }}">Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
