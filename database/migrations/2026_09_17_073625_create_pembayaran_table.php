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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('booking')->cascadeOnDelete();
            $table->enum('metode', ['transfer', 'tunai']);
            $table->string('bukti_transfer', 255)->nullable();
            $table->decimal('jumlah', 10, 2)->default(0);
            $table->enum('status_verifikasi', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('diverifikasi_pada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
