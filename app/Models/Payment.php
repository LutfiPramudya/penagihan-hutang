<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'debt_id',
        'amount',
        'date',
        'note',
        'proof_path',
        'is_verified',
        'verified_at',
        'recorded_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }
}
