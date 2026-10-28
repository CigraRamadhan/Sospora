<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanSistem extends Model
{
    protected $table = 'pengaturan_sistem';

    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'kontak_email',
        'kontak_telepon',
        'alamat_kantor',
        'jam_layanan',
    ];

    public static function getSettings(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'nama_bank' => 'Bank BJB (Bank Pembangunan Daerah Jawa Barat dan Banten)',
                'nomor_rekening' => '0012345678901',
                'atas_nama' => 'Dispora Kota Bandung - Penerimaan Retribusi Sarana',
                'kontak_email' => 'dispora@bandung.go.id',
                'kontak_telepon' => '(022) 4203366',
                'alamat_kantor' => 'Jl. Tamansari No. 76, Kota Bandung, Jawa Barat 40132',
                'jam_layanan' => 'Senin - Jumat: 08.00 - 16.00 WIB',
            ]
        );
    }
}
