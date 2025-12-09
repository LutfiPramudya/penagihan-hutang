{{-- resources/views/customers/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Daftar Debitur')

@section('content')
<div class="container py-4" style="max-width:1200px;">
    <div class="card shadow-lg border-0 rounded-4 p-4" style="background:#ffffffcc;backdrop-filter:blur(10px);">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"> Kelola Debitur</h2>
        </div>

        <table class="table table-hover table-bordered rounded-4 overflow-hidden shadow-sm text-center align-middle">
            <thead class="bg-dark text-white">
                <tr>
                    <th style="width:60px;">No.</th>
                    <th>Nama Debitur</th>
                    <th>No. HP</th>
                    <th>Alamat</th>
                    <th>Status Verifikasi</th>
                    <th>Limit</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-bold">{{ $customer->name }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>
                        <span class="badge 
                            @if($customer->status_verifikasi==='disetujui') bg-success 
                            @elseif($customer->status_verifikasi==='ditolak') bg-danger
                            @else bg-warning text-dark @endif">
                            {{ ucfirst($customer->status_verifikasi ?? 'menunggu') }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($customer->limit_pinjaman ?? 0,0,',','.') }}</td>
                    <td>{{ $customer->note }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">

                            <a href="{{ route('debts.index', $customer->id) }}"
                               class="btn btn-outline-info rounded-pill btn-sm d-flex align-items-center gap-1">
                                Lihat Hutang
                            </a>

                            <a href="{{ route('customers.edit', $customer->id) }}"
                               class="btn btn-outline-primary rounded-pill btn-sm d-flex align-items-center gap-1">
                                Edit
                            </a>

                            <form action="{{ route('customers.destroy', $customer->id) }}"
                                  method="POST" onsubmit="return confirm('Hapus debitur ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger rounded-pill btn-sm d-flex align-items-center gap-1">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-3">Belum ada data debitur</td>
                </tr>
            @endforelse
            </tbody>

        </table>

    </div>
</div>
@endsection
