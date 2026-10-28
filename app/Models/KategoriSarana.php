<?php

namespace App\Models;

use Database\Factories\KategoriSaranaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriSarana extends Model
{
    /** @use HasFactory<KategoriSaranaFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_sarana';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'icon',
        'deskripsi',
    ];

    public function sarana(): HasMany
    {
        return $this->hasMany(Sarana::class, 'kategori_id');
    }

    public function saranas(): HasMany
    {
        return $this->hasMany(Sarana::class, 'kategori_id');
    }
}
