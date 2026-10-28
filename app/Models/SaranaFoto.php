<?php

namespace App\Models;

use Database\Factories\SaranaFotoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaranaFoto extends Model
{
    /** @use HasFactory<SaranaFotoFactory> */
    use HasFactory;

    protected $table = 'sarana_foto';

    protected $fillable = [
        'sarana_id',
        'path_file',
        'urutan',
    ];

    public function sarana(): BelongsTo
    {
        return $this->belongsTo(Sarana::class, 'sarana_id');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->path_file);
    }
}
