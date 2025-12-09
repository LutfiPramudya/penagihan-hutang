<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Penagihan Hutang')</title>

    {{-- BOOTSTRAP CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- GOOGLE FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- CUSTOM STYLE --}}
    <style>
        body {
            background: linear-gradient(135deg, #d1eaff, #f7faff);
            font-family: 'Poppins', sans-serif;
        }

        /* SIDEBAR */
        #sidebar {
            position: fixed;
            top: 0;
            left: -260px;
            width: 250px;
            height: 100%;
            background: white;
            padding: 20px;
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);
            transition: 0.3s ease;
            z-index: 999;
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
        }
        #sidebar.show {
            left: 0;
        }

        /* OVERLAY */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            display: none;
            z-index: 998;
        }

        nav {
            background: #4982deff;
            color: white;
            font-size: 22px;
            padding: 15px 25px;
            font-weight: 700;
            border-bottom-left-radius: 18px;
            border-bottom-right-radius: 18px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .hamburger {
            font-size: 28px;
            cursor: pointer;
            transition: transform 0.0s ease;
        }

        .hamburger.active {
            transform: none;
        }
    </style>
    @php
        $isLoginPage = request()->routeIs('login');
    @endphp
</head>

<body class="{{ $isLoginPage ? 'bg-light' : '' }}">

   {{-- SIDEBAR --}}
@auth
    @if(!$isLoginPage)
    <div id="sidebar">

        {{-- HAMBURGER INSIDE SIDEBAR --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <span class="hamburger" onclick="toggleSidebar()" style="font-size: 26px; cursor:pointer;" aria-label="Toggle sidebar">&#9776;</span>
        </div>

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('dashboard') }}" class="d-block mb-3 fw-semibold text-decoration-none">
                Dashboard
            </a>

            <a href="{{ route('customers.index') }}" class="d-block mb-3 fw-semibold text-decoration-none">
                Kelola Debitur
            </a>

            <a href="{{ route('admin.loans.index') }}" class="d-block mb-3 fw-semibold text-decoration-none">
                Kelola Pinjaman
            </a>

            <a href="{{ route('admin.loans.payments.verify') }}" class="d-block mb-3 fw-semibold text-decoration-none">
                Verifikasi Pembayaran
            </a>
        @endif

        @if(auth()->user()->role === 'customer')
            <hr>
            <div class="small text-muted text-uppercase mb-2">Area Klien</div>
            <a href="{{ route('client.profile') }}" class="d-block mb-2 text-decoration-none">
                Isi Data Diri
            </a>
            <a href="{{ route('client.loans.index') }}" class="d-block mb-2 text-decoration-none">
                Peminjaman Saya
            </a>
            <a href="{{ route('client.payments') }}" class="d-block mb-2 text-decoration-none">
                Riwayat Pembayaran
            </a>
        @endif
    </div>
    @endif
@endauth


    {{-- OVERLAY --}}
    @if(auth()->check() && !$isLoginPage)
        <div id="overlay" onclick="toggleSidebar()"></div>
    @endif

    {{-- NAVBAR --}}
    <nav class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            @if(auth()->check() && !$isLoginPage)
                <span class="hamburger" onclick="toggleSidebar()" aria-label="Toggle sidebar">&#9776;</span>
            @endif
            Aplikasi Penagihan Hutang
        </div>

        @if(auth()->check() && !$isLoginPage)
            <form action="{{ route('logout') }}" method="POST" class="d-flex align-items-center gap-2 m-0">
                @csrf
                <span class="small text-white">
                    {{ auth()->user()->role === 'admin' ? 'Admin: ' : 'Client: ' }}{{ auth()->user()->name }}
                </span>
                <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
            </form>
        @endif
    </nav>

    {{-- PAGE CONTENT --}}
    <div class="container mt-4">
        @yield('content')
    </div>

    {{-- BOOTSTRAP JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SIDEBAR SCRIPT --}}
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const hamburgers = document.querySelectorAll('.hamburger');

            sidebar.classList.toggle('show');

            if (sidebar.classList.contains('show')) {
                overlay.style.display = 'block';
                hamburgers.forEach(h => h.classList.add('active'));
            } else {
                overlay.style.display = 'none';
                hamburgers.forEach(h => h.classList.remove('active'));
            }
        }
    </script>

</body>
</html>
