<?php

namespace App\Models;

use Database\Factories\SaranaFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sarana extends Model
{
    /** @use HasFactory<SaranaFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'sarana';

    protected $fillable = [
        'kategori_id',
        'nama_sarana',
        'slug',
        'deskripsi',
        'lokasi',
        'harga_per_slot',
        'jam_buka',
        'jam_tutup',
        'interval_slot_menit',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'harga_per_slot' => 'decimal:2',
            'interval_slot_menit' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSarana::class, 'kategori_id');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(SaranaFoto::class, 'sarana_id')->orderBy('urutan');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'sarana_id');
    }

    public function getFotoUtamaAttribute(): ?string
    {
        $foto = $this->fotos->first();

        return $foto ? asset('storage/'.$foto->path_file) : null;
    }
}
