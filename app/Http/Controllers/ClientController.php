<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    private function currentCustomer(Request $request): ?Customer
    {
        $user = $request->user();
        if (!$user) {
            return null;
        }

        $customer = $user->customer;

        if (!$customer) {
            $customer = Customer::create([
                'user_id'  => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'phone'    => $user->phone,
                'alamat_ktp'       => 'Belum diisi',
                'nik'              => $user->phone ?? $user->email ?? '0000000000000000',
                'tempat_lahir'     => 'Belum diisi',
                'tanggal_lahir'    => now()->toDateString(),
                'jenis_kelamin'    => 'Laki-laki',
                'golongan_darah'   => 'O',
                'rt'               => '00',
                'rw'               => '00',
                'kelurahan'        => 'Belum diisi',
                'kecamatan'        => 'Belum diisi',
                'agama'            => 'Islam',
                'status_perkawinan'=> 'Belum Menikah',
                'pekerjaan'        => 'Belum Diisi',
                'kewarganegaraan'  => 'WNI',
                'masa_berlaku'     => 'Seumur Hidup',
                'foto_ktp'         => 'default.jpg',
                'pendidikan_terakhir' => 'Belum Diisi',
                'gaji_per_bulan'      => 0,
                'nama_bank'           => '-',
                'pemilik_rekening'    => '-',
                'nomor_rekening'      => '-',
                'status_verifikasi'   => 'menunggu',
                'limit_pinjaman'      => 0,
            ]);
        }

        return $customer;
    }

    public function profile(Request $request)
    {
        return view('user.profile', [
            'customer' => $this->currentCustomer($request),
        ]);
    }

    public function storeProfile(Request $request)
    {
        $customer = $this->currentCustomer($request);
        $customerId = $customer?->id;

        if ($customer && $customer->status_verifikasi === 'disetujui') {
            return redirect()
                ->route('client.profile')
                ->with('warning', 'Data sudah disetujui admin. Hubungi admin jika ingin mengubah data.');
        }

        $data = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'phone'               => ['required', 'string', 'max:50'],
            'alamat_ktp'          => ['required', 'string'],
            'nik'                 => ['required', 'string', 'max:20', Rule::unique('customers', 'nik')->ignore($customerId)],
            'tempat_lahir'        => ['required', 'string', 'max:100'],
            'tanggal_lahir'       => ['required', 'date'],
            'jenis_kelamin'       => ['required', 'in:Laki-laki,Perempuan'],
            'golongan_darah'      => ['required', 'in:A,B,AB,O,-'],
            'rt'                  => ['required', 'string', 'max:5'],
            'rw'                  => ['required', 'string', 'max:5'],
            'kelurahan'           => ['required', 'string', 'max:100'],
            'kecamatan'           => ['required', 'string', 'max:100'],
            'agama'               => ['required', Rule::in(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'])],
            'status_perkawinan'   => ['required', Rule::in(['Menikah', 'Belum Menikah'])],
            'pekerjaan'           => ['required', 'string', 'max:100'],
            'kewarganegaraan'     => ['required', Rule::in(['WNI', 'WNA'])],
            'note'                => ['nullable', 'string'],
        ]);

        // Set ke menunggu setiap kali user update biodata agar admin review ulang
        $data = array_merge([
            'status_verifikasi'   => 'menunggu',
            'limit_pinjaman'      => 0,
            'disetujui_pada'      => null,
            'alasan_penolakan'    => null,
            'foto_ktp'            => $customer?->foto_ktp ?? 'default.jpg',
            'masa_berlaku'        => $customer?->masa_berlaku ?? 'Seumur Hidup',
            // default values untuk form keuangan (akan diperbarui di halaman kedua)
            'pendidikan_terakhir' => $customer?->pendidikan_terakhir ?? 'Belum Diisi',
            'gaji_per_bulan'      => $customer?->gaji_per_bulan ?? 0,
            'nama_bank'           => $customer?->nama_bank ?? '-',
            'pemilik_rekening'    => $customer?->pemilik_rekening ?? '-',
            'nomor_rekening'      => $customer?->nomor_rekening ?? '-',
        ], $data);

        if ($customer) {
            $customer->update($data);
        } else {
            $customer = Customer::create($data);
        }

        $request->session()->put('client_customer_id', $customer->id);

        return redirect()
            ->route('client.profile.finance')
            ->with('success', 'Data diri berhasil disimpan. Lengkapi data keuangan.');
    }

    public function finance(Request $request)
    {
        $customer = $this->currentCustomer($request);

        if (!$customer) {
            return redirect()->route('client.profile')->with('warning', 'Lengkapi data diri terlebih dahulu.');
        }

        return view('user.profile_finance', compact('customer'));
    }

    public function storeFinance(Request $request)
    {
        $customer = $this->currentCustomer($request);

        if (!$customer) {
            return redirect()->route('client.profile')->with('warning', 'Lengkapi data diri terlebih dahulu.');
        }

        if ($customer->status_verifikasi === 'disetujui') {
            return redirect()->route('client.profile.finance')->with('warning', 'Data sudah disetujui admin. Hubungi admin jika ingin mengubah data.');
        }

        $data = $request->validate([
            'pendidikan_terakhir' => ['required', 'string', 'max:100'],
            'gaji_per_bulan'      => ['required', 'numeric', 'min:0'],
            'nama_bank'           => ['required', 'string', 'max:100'],
            'pemilik_rekening'    => ['required', 'string', 'max:255'],
            'nomor_rekening'      => ['required', 'string', 'max:50'],
        ]);

        $customer->update($data);

        return redirect()
            ->route('client.loans.index')
            ->with('success', 'Data keuangan berhasil disimpan. Anda bisa mengajukan pinjaman.');
    }

    public function loans(Request $request)
    {
        $customer = $this->currentCustomer($request);

        if (!$customer) {
            return redirect()->route('client.profile')->with('warning', 'Lengkapi data diri sebelum mengajukan pinjaman.');
        }

        $debts = $customer->debts()->with('payments')->orderByDesc('created_at')->get();

        return view('user.peminjaman.index', compact('customer', 'debts'));
    }

    public function createLoan(Request $request)
    {
        $customer = $this->currentCustomer($request);

        if (!$customer) {
            return redirect()->route('client.profile')->with('warning', 'Lengkapi data diri sebelum mengajukan pinjaman.');
        }

        return view('user.peminjaman.create', compact('customer'));
    }

    public function storeLoan(Request $request)
    {
        $customer = $this->currentCustomer($request);

        if (!$customer) {
            return redirect()->route('client.profile')->with('warning', 'Lengkapi data diri sebelum mengajukan pinjaman.');
        }

        if ($customer->status_verifikasi !== 'disetujui' || ($customer->limit_pinjaman ?? 0) <= 0) {
            return redirect()->route('client.profile')->with('warning', 'Pengajuan menunggu persetujuan admin atau limit belum ditetapkan.');
        }

        $data = $request->validate([
            'amount'      => ['required', 'integer', 'min:1'],
            'tenor_bulan' => ['required', 'integer', Rule::in([3, 6, 12])],
            'note'        => ['nullable', 'string'],
        ]);

        if ($data['amount'] > $customer->limit_pinjaman) {
            return back()->withErrors(['amount' => 'Nominal melebihi limit pinjaman yang diberikan admin.'])->withInput();
        }

        $rateMap = [
            3  => 2.0,
            6  => 5.0,
            12 => 7.0,
        ];

        $tenor = (int) $data['tenor_bulan'];
        $bungaPersen = $rateMap[$tenor];

        $totalPengembalian = $data['amount'] + ($data['amount'] * ($bungaPersen / 100));
        $cicilan = $tenor > 0 ? $totalPengembalian / $tenor : 0;

        $debt = Debt::create([
            'customer_id'         => $customer->id,
            'amount'              => $data['amount'],
            'tenor_bulan'         => $tenor,
            'bunga_persen'        => $bungaPersen,
            'total_pengembalian'  => $totalPengembalian,
            'cicilan_per_bulan'   => $cicilan,
            'tanggal_pengajuan'   => now(),
            'tanggal_jatuh_tempo' => now()->addMonths($tenor),
            'date'                => now()->toDateString(),
            'status'              => 'belum lunas',
            'note'                => $data['note'] ?? null,
        ]);

        return redirect()
            ->route('client.loans.show', $debt->id)
            ->with('success', 'Pengajuan pinjaman dikirim.');
    }

    public function showLoan(Request $request, Debt $debt)
    {
        $customer = $this->currentCustomer($request);

        if (!$customer) {
            return redirect()->route('client.profile')->with('warning', 'Lengkapi data diri sebelum mengajukan pinjaman.');
        }

        abort_unless($debt->customer_id === $customer->id, 403, 'Pinjaman tidak ditemukan.');

        $debt->load('payments');

        return view('user.peminjaman.show', compact('customer', 'debt'));
    }

    public function paymentHistory(Request $request)
    {
        $customer = $this->currentCustomer($request);

        if (!$customer) {
            return redirect()->route('client.profile')->with('warning', 'Lengkapi data diri sebelum melihat riwayat pembayaran.');
        }

        $payments = Payment::with('debt')
            ->whereHas('debt', function ($query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->where('recorded_by', 'customer')
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->get();

        return view('user.peminjaman.payments', compact('customer', 'payments'));
    }
}
