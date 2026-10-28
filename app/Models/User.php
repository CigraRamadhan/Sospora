<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'name',
        'email',
        'no_hp',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Synchronize name and nama attributes.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value, array $attributes) => $attributes['nama'] ?? $value,
            set: fn (?string $value) => [
                'name' => $value,
                'nama' => $value,
            ],
        );
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin' || $this->hasRole('superadmin');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    public function isUser(): bool
    {
        return $this->role === 'user' || $this->hasRole('user');
    }

    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function verifiedBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'diverifikasi_oleh');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'user_id');
    }
}
