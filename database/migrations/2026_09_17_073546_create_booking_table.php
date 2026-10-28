<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('sarana_id')->constrained('sarana')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('slot_mulai');
            $table->time('slot_selesai');
            $table->integer('jumlah_peserta')->nullable();
            $table->text('catatan_user')->nullable();
            $table->enum('metode_bayar', ['transfer', 'bayar_di_tempat']);
            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_konfirmasi',
                'disetujui',
                'ditolak',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_pembayaran');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('diverifikasi_pada')->nullable();

            // Anti-conflict active slot unique key (BOK-05)
            // Stored as "{sarana_id}_{tanggal}_{slot_mulai}" for active bookings, NULL if cancelled/rejected
            $table->string('slot_active_key', 100)->nullable()->unique();

            $table->timestamps();
            $table->index(['sarana_id', 'tanggal']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
