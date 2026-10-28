<?php

use App\Models\Booking;
use App\Models\KategoriSarana;
use App\Models\Sarana;
use App\Models\User;
use App\Services\KetersediaanSlotService;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->kategori = KategoriSarana::create([
        'nama_kategori' => 'Futsal',
        'slug' => 'futsal',
        'icon' => 'trophy',
    ]);

    $this->sarana = Sarana::create([
        'kategori_id' => $this->kategori->id,
        'nama_sarana' => 'Lapangan Futsal Saparua',
        'slug' => 'lapangan-futsal-saparua',
        'deskripsi' => 'Lapangan vinyl standar nasional',
        'lokasi' => 'Jl. Banda No. 28, Citarum, Kec. Bandung Wetan',
        'harga_per_slot' => 150000,
        'jam_buka' => '08:00:00',
        'jam_tutup' => '12:00:00',
        'interval_slot_menit' => 60,
        'status' => 'aktif',
    ]);

    $this->user = User::create([
        'nama' => 'Warga Bandung',
        'email' => 'warga@example.com',
        'password' => bcrypt('password'),
        'role' => 'user',
        'status' => 'aktif',
    ]);

    $this->service = new KetersediaanSlotService;
});

test('service generates correct number of slots based on hours and interval', function () {
    $tomorrow = now()->addDay()->format('Y-m-d');
    $slots = $this->service->getSlotsForDate($this->sarana, $tomorrow);

    // 08:00 - 12:00 with 60 min intervals = 4 slots (08-09, 09-10, 10-11, 11-12)
    expect($slots)->toHaveCount(4);
    expect($slots[0]['slot_mulai'])->toBe('08:00');
    expect($slots[0]['slot_selesai'])->toBe('09:00');
    expect($slots[0]['status'])->toBe('tersedia');
});

test('active booking marks slot as terisi', function () {
    $tomorrow = now()->addDay()->format('Y-m-d');

    Booking::create([
        'kode_booking' => 'SOSPORA-TEST-0001',
        'user_id' => $this->user->id,
        'sarana_id' => $this->sarana->id,
        'tanggal' => $tomorrow,
        'slot_mulai' => '09:00:00',
        'slot_selesai' => '10:00:00',
        'metode_bayar' => 'transfer',
        'status' => 'menunggu_pembayaran',
        'slot_active_key' => $this->sarana->id.'_'.$tomorrow.'_09:00',
    ]);

    $slots = $this->service->getSlotsForDate($this->sarana, $tomorrow);

    $slot08 = collect($slots)->firstWhere('slot_mulai', '08:00');
    $slot09 = collect($slots)->firstWhere('slot_mulai', '09:00');

    expect($slot08['status'])->toBe('tersedia');
    expect($slot09['status'])->toBe('terisi');
});

test('cancelled or rejected booking frees up slot as tersedia', function () {
    $tomorrow = now()->addDay()->format('Y-m-d');

    Booking::create([
        'kode_booking' => 'SOSPORA-TEST-0002',
        'user_id' => $this->user->id,
        'sarana_id' => $this->sarana->id,
        'tanggal' => $tomorrow,
        'slot_mulai' => '10:00:00',
        'slot_selesai' => '11:00:00',
        'metode_bayar' => 'transfer',
        'status' => 'dibatalkan',
        'slot_active_key' => null, // Freed upon cancellation
    ]);

    $slots = $this->service->getSlotsForDate($this->sarana, $tomorrow);
    $slot10 = collect($slots)->firstWhere('slot_mulai', '10:00');

    expect($slot10['status'])->toBe('tersedia');
});

test('lockAndValidateSlot prevents double booking atomically (BOK-05)', function () {
    $tomorrow = now()->addDay()->format('Y-m-d');

    DB::transaction(function () use ($tomorrow) {
        $available = $this->service->lockAndValidateSlot($this->sarana->id, $tomorrow, '08:00');
        expect($available)->toBeTrue();

        // Create booking in transaction
        Booking::create([
            'kode_booking' => 'SOSPORA-TEST-0003',
            'user_id' => $this->user->id,
            'sarana_id' => $this->sarana->id,
            'tanggal' => $tomorrow,
            'slot_mulai' => '08:00:00',
            'slot_selesai' => '09:00:00',
            'metode_bayar' => 'transfer',
            'status' => 'menunggu_konfirmasi',
            'slot_active_key' => $this->sarana->id.'_'.$tomorrow.'_08:00',
        ]);
    });

    // Check again after booking created
    DB::transaction(function () use ($tomorrow) {
        $available = $this->service->lockAndValidateSlot($this->sarana->id, $tomorrow, '08:00');
        expect($available)->toBeFalse();
    });
});
