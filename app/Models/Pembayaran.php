<?php

namespace App\Models;

use Database\Factories\PembayaranFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    /** @use HasFactory<PembayaranFactory> */
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'booking_id',
        'metode',
        'bukti_transfer',
        'jumlah',
        'status_verifikasi',
        'diverifikasi_oleh',
        'diverifikasi_pada',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'diverifikasi_pada' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function getBuktiTransferUrlAttribute(): ?string
    {
        return $this->bukti_transfer ? asset('storage/'.$this->bukti_transfer) : null;
    }
}
