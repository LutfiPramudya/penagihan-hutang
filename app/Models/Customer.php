<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Debt;
use App\Models\Payment;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    // kalau tabelnya memang "customers", ini opsional
    protected $table = 'customers';

    /**
     * Sesuaikan dengan kolom customers versi baru kamu.
     * Aku pakai nama kolom yang kamu minta:
     * - address -> alamat_ktp
     * - note tetap dipakai karena model awal kamu pakai "note"
     */
    protected $fillable = [
        // data dasar
        'name',
        'phone',
        'email',            // kalau kamu punya kolom email di customers
        'password',         // kalau kamu simpan password di customers
        'user_id',
        'remember_token',

        // alamat KTP (pengganti address)
        'alamat_ktp',

        // data KTP & identitas
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'golongan_darah',

        'rt',
        'rw',
        'kelurahan',
        'kecamatan',

        'agama',
        'status_perkawinan',
        'pekerjaan',
        'kewarganegaraan',
        'masa_berlaku',
        'foto_ktp',

        // pendidikan & gaji
        'pendidikan_terakhir',
        'gaji_per_bulan',

        // data bank
        'nama_bank',
        'pemilik_rekening',
        'nomor_rekening',

        // verifikasi & limit
        'status_verifikasi',
        'limit_pinjaman',
        'disetujui_pada',
        'alasan_penolakan',

        // catatan
        'note',
    ];

    /**
     * Casting biar enak dipakai di controller/blade
     */
    protected $casts = [
        'tanggal_lahir'   => 'date',
        'disetujui_pada'  => 'datetime',
        'gaji_per_bulan'  => 'decimal:2',
        'limit_pinjaman'  => 'decimal:2',
        'password'        => 'hashed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi 1 customer punya banyak pinjaman (debts)
     */
    public function debts()
    {
        return $this->hasMany(Debt::class, 'customer_id', 'id');
    }

    /**
     * Relasi riwayat pembayaran melalui debts
     */
    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,
            Debt::class,
            'customer_id', // FK di debts
            'debt_id',     // FK di payments
            'id',          // PK di customers
            'id'           // PK di debts
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
