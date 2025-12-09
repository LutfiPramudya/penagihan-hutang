@extends('layouts.app')

@section('title', 'Login Klien')

@section('content')
<div class="container py-5" style="max-width: 480px;">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-3 text-center">Login Klien</h3>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('client.login.submit') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email atau No. HP</label>
                    <input type="text" name="login" class="form-control" value="{{ old('login') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-primary w-100">Masuk</button>
            </form>

            <div class="text-center mt-3">
                Belum punya akun? <a href="{{ route('client.register') }}">Registrasi</a>
            </div>
        </div>
    </div>
</div>
@endsection
