<?php

namespace App\Models;

use Database\Factories\NotifikasiFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    /** @use HasFactory<NotifikasiFactory> */
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'booking_id',
        'tipe',
        'pesan',
        'dibaca',
    ];

    protected function casts(): array
    {
        return [
            'dibaca' => 'boolean',
        ];
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('dibaca', false);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
