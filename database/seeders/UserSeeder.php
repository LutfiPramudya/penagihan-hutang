<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin',
                'phone'    => '08123456789',
                'role'     => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        $customerUser = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name'     => 'Customer Demo',
                'phone'    => '08111111111',
                'role'     => 'customer',
                'password' => Hash::make('password'),
            ]
        );

        Customer::firstOrCreate(
            ['user_id' => $customerUser->id],
            [
                'name'                 => $customerUser->name,
                'email'                => $customerUser->email,
                'phone'                => $customerUser->phone,
                'alamat_ktp'           => 'Belum diisi',
                'nik'                  => $customerUser->phone ?? $customerUser->email ?? '0000000000000000',
                'tempat_lahir'         => 'Belum diisi',
                'tanggal_lahir'        => now()->toDateString(),
                'jenis_kelamin'        => 'Laki-laki',
                'golongan_darah'       => 'O',
                'rt'                   => '00',
                'rw'                   => '00',
                'kelurahan'            => 'Belum diisi',
                'kecamatan'            => 'Belum diisi',
                'agama'                => 'Islam',
                'status_perkawinan'    => 'Belum Menikah',
                'pekerjaan'            => 'Belum Diisi',
                'kewarganegaraan'      => 'WNI',
                'masa_berlaku'         => 'Seumur Hidup',
                'foto_ktp'             => 'default.jpg',
                'pendidikan_terakhir'  => 'Belum Diisi',
                'gaji_per_bulan'       => 0,
                'nama_bank'            => '-',
                'pemilik_rekening'     => '-',
                'nomor_rekening'       => '-',
                'status_verifikasi'    => 'menunggu',
                'limit_pinjaman'       => 0,
                'note'                 => null,
                'disetujui_pada'       => null,
                'alasan_penolakan'     => null,
            ]
        );
    }
}
