<?php

namespace Database\Seeders;

use App\Models\KategoriSarana;
use App\Models\PengaturanSistem;
use App\Models\Sarana;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Spatie roles
        $roles = ['superadmin', 'admin', 'user'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // === Demo Accounts ===
        $superAdmin = User::create([
            'nama' => 'Super Admin Dispora',
            'name' => 'Super Admin Dispora',
            'email' => 'superadmin@sospora.bandung.go.id',
            'no_hp' => '022-7272-xxxx',
            'password' => Hash::make('superadmin123'),
            'role' => 'superadmin',
            'status' => 'aktif',
        ]);
        $superAdmin->assignRole('superadmin');

        $admin = User::create([
            'nama' => 'Admin Dispora Kota Bandung',
            'name' => 'Admin Dispora Kota Bandung',
            'email' => 'admin@sospora.bandung.go.id',
            'no_hp' => '022-7272-xxxx',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);
        $admin->assignRole('admin');

        $warga = User::create([
            'nama' => 'Budi Santoso',
            'name' => 'Budi Santoso',
            'email' => 'warga@sospora.bandung.go.id',
            'no_hp' => '0812-3456-7890',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'status' => 'aktif',
        ]);
        $warga->assignRole('user');

        // === Kategori Sarana ===
        $kategoriFutsal = KategoriSarana::create(['nama_kategori' => 'Futsal', 'icon' => '⚽']);
        $kategoriBasket = KategoriSarana::create(['nama_kategori' => 'Basket', 'icon' => '🏀']);
        $kategoriBadminton = KategoriSarana::create(['nama_kategori' => 'Badminton', 'icon' => '🏸']);
        $kategoriRenang = KategoriSarana::create(['nama_kategori' => 'Renang', 'icon' => '🏊']);
        $kategoriTenis = KategoriSarana::create(['nama_kategori' => 'Tenis Lapangan', 'icon' => '🎾']);
        $kategoriVoli = KategoriSarana::create(['nama_kategori' => 'Voli', 'icon' => '🏐']);

        // === Sarana Olahraga ===
        Sarana::create([
            'kategori_id' => $kategoriFutsal->id,
            'nama_sarana' => 'Lapangan Futsal GOR Saparua',
            'slug' => 'lapangan-futsal-gor-saparua',
            'deskripsi' => 'Lapangan futsal indoor berstandar nasional dengan rumput sintetis berkualitas tinggi di GOR Saparua. Dilengkapi dengan tribun penonton, ruang ganti, dan kantin. Cocok untuk latihan tim maupun turnamen antar warga.',
            'lokasi' => 'Jl. Saparua No.2, Citarum, Bandung Wetan, Kota Bandung',
            'harga_per_slot' => 150000,
            'jam_buka' => '07:00',
            'jam_tutup' => '22:00',
            'interval_slot_menit' => 60,
            'status' => 'aktif',
        ]);

        Sarana::create([
            'kategori_id' => $kategoriBasket->id,
            'nama_sarana' => 'Lapangan Basket GOR Saparua',
            'slug' => 'lapangan-basket-gor-saparua',
            'deskripsi' => 'Lapangan basket outdoor berstandar FIBA di kompleks GOR Saparua. Fasilitas pendukung meliputi tribun penonton, papan skor elektronik, dan area parkir luas.',
            'lokasi' => 'Jl. Saparua No.2, Citarum, Bandung Wetan, Kota Bandung',
            'harga_per_slot' => 100000,
            'jam_buka' => '07:00',
            'jam_tutup' => '21:00',
            'interval_slot_menit' => 60,
            'status' => 'aktif',
        ]);

        Sarana::create([
            'kategori_id' => $kategoriBadminton->id,
            'nama_sarana' => 'Lapangan Badminton Taman Cibeunying',
            'slug' => 'lapangan-badminton-taman-cibeunying',
            'deskripsi' => 'Lapangan badminton indoor dengan 4 court berstandar BWF di Taman Cibeunying. Lantai kayu berkualitas, pencahayaan LED memadai, dan AC sentral untuk kenyamanan bermain.',
            'lokasi' => 'Jl. Cibeunying, Cigadung, Cibeunying Kaler, Kota Bandung',
            'harga_per_slot' => 75000,
            'jam_buka' => '06:00',
            'jam_tutup' => '22:00',
            'interval_slot_menit' => 60,
            'status' => 'aktif',
        ]);

        Sarana::create([
            'kategori_id' => $kategoriRenang->id,
            'nama_sarana' => 'Kolam Renang Dispora Kota Bandung',
            'slug' => 'kolam-renang-dispora-kota-bandung',
            'deskripsi' => 'Kolam renang olimpik berukuran 50 meter milik Dispora Kota Bandung. Dilengkapi kolam anak-anak, papan loncat, tribun, dan ruang ganti terpisah putra-putri.',
            'lokasi' => 'Jl. Lodaya No.2, Burangrang, Lengkong, Kota Bandung',
            'harga_per_slot' => 25000,
            'jam_buka' => '06:00',
            'jam_tutup' => '18:00',
            'interval_slot_menit' => 120,
            'status' => 'aktif',
        ]);

        Sarana::create([
            'kategori_id' => $kategoriFutsal->id,
            'nama_sarana' => 'Lapangan Futsal Lodaya',
            'slug' => 'lapangan-futsal-lodaya',
            'deskripsi' => 'Lapangan futsal outdoor dengan permukaan rumput sintetis. Lokasi strategis di kawasan Lodaya, mudah dijangkau dari berbagai penjuru Kota Bandung.',
            'lokasi' => 'Jl. Lodaya No.2, Burangrang, Lengkong, Kota Bandung',
            'harga_per_slot' => 120000,
            'jam_buka' => '08:00',
            'jam_tutup' => '21:00',
            'interval_slot_menit' => 60,
            'status' => 'aktif',
        ]);

        Sarana::create([
            'kategori_id' => $kategoriVoli->id,
            'nama_sarana' => 'Lapangan Voli Stadion GBLA',
            'slug' => 'lapangan-voli-stadion-gbla',
            'deskripsi' => 'Lapangan voli indoor di kompleks Stadion GBLA Gedebage. Lantai kayu springwood, net standar FIVB, dan pencahayaan stadium profesional.',
            'lokasi' => 'Jl. Gedebage Selatan No.1, Gedebage, Kota Bandung',
            'harga_per_slot' => 90000,
            'jam_buka' => '07:00',
            'jam_tutup' => '21:00',
            'interval_slot_menit' => 60,
            'status' => 'aktif',
        ]);

        Sarana::create([
            'kategori_id' => $kategoriTenis->id,
            'nama_sarana' => 'Lapangan Tenis Sidolig',
            'slug' => 'lapangan-tenis-sidolig',
            'deskripsi' => 'Lapangan tenis outdoor 3 court di Lapangan Sidolig. Permukaan aspal berkualitas, net standar ITF, dan lampu sorot untuk sesi malam hari.',
            'lokasi' => 'Jl. Ahmad Yani, Cicadas, Kota Bandung',
            'harga_per_slot' => 80000,
            'jam_buka' => '06:00',
            'jam_tutup' => '20:00',
            'interval_slot_menit' => 60,
            'status' => 'aktif',
        ]);

        // === Pengaturan Sistem ===
        PengaturanSistem::create([
            'nama_bank' => 'Bank BJB',
            'nomor_rekening' => '0032-10-00001-1',
            'atas_nama' => 'Dinas Pemuda dan Olahraga Kota Bandung',
            'kontak_email' => 'dispora@bandung.go.id',
            'kontak_telepon' => '022-7274-5678',
            'alamat_kantor' => 'Jl. Lodaya No.2, Burangrang, Lengkong, Kota Bandung 40262',
            'jam_layanan' => 'Senin - Jumat: 08.00 - 16.00 WIB',
        ]);
    }
}
