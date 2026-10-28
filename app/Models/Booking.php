<?php

namespace App\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    protected $table = 'booking';

    protected $fillable = [
        'kode_booking',
        'user_id',
        'sarana_id',
        'tanggal',
        'slot_mulai',
        'slot_selesai',
        'jumlah_peserta',
        'catatan_user',
        'metode_bayar',
        'status',
        'catatan_admin',
        'diverifikasi_oleh',
        'diverifikasi_pada',
        'slot_active_key',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date:Y-m-d',
            'diverifikasi_pada' => 'datetime',
            'jumlah_peserta' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', ['dibatalkan', 'ditolak']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sarana(): BelongsTo
    {
        return $this->belongsTo(Sarana::class, 'sarana_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'booking_id');
    }

    public function canBeCancelledByUser(): bool
    {
        return in_array($this->status, ['menunggu_pembayaran', 'menunggu_konfirmasi'], true);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'menunggu_pembayaran' => 'bg-amber-100 text-amber-700 border-amber-200',
            'menunggu_konfirmasi' => 'bg-blue-100 text-blue-700 border-blue-200',
            'disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'ditolak' => 'bg-red-100 text-red-700 border-red-200',
            'selesai' => 'bg-slate-100 text-slate-600 border-slate-200',
            'dibatalkan' => 'bg-slate-100 text-slate-400 line-through border-slate-200',
            default => 'bg-slate-100 text-slate-600 border-slate-200',
        };
    }

    public function getTotalHargaAttribute(): float
    {
        if ($this->pembayaran && (float) $this->pembayaran->jumlah > 0) {
            return (float) $this->pembayaran->jumlah;
        }

        $peserta = max(1, (int) ($this->jumlah_peserta ?? 1));

        return (float) (($this->sarana?->harga_per_slot ?? 0) * $peserta);
    }

    public static function generateKodeBooking(): string
    {
        $date = now()->format('Ymd');
        $lastBooking = self::whereDate('created_at', today())->latest('id')->first();
        $sequence = $lastBooking ? ((int) substr($lastBooking->kode_booking, -4)) + 1 : 1;

        return sprintf('SOSPORA-%s-%04d', $date, $sequence);
    }
}
