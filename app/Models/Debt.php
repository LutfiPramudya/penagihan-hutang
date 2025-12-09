<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'profil_id',
        'amount',
        'tenor_bulan',
        'bunga_persen',
        'total_pengembalian',
        'cicilan_per_bulan',
        'tanggal_pengajuan',
        'disetujui_pada',
        'tanggal_jatuh_tempo',
        'date',
        'status',
        'approval_status',
        'approved_at',
        'rejected_reason',
        'note',
    ];

    protected $casts = [
        'tanggal_pengajuan'   => 'datetime',
        'disetujui_pada'      => 'datetime',
        'tanggal_jatuh_tempo' => 'date',
        'date'                => 'date',
        'bunga_persen'        => 'decimal:2',
        'total_pengembalian'  => 'decimal:2',
        'cicilan_per_bulan'   => 'decimal:2',
        'approved_at'         => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
