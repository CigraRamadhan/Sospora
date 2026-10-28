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
        Schema::create('pengaturan_sistem', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank', 100)->nullable();
            $table->string('nomor_rekening', 100)->nullable();
            $table->string('atas_nama', 150)->nullable();
            $table->string('kontak_email', 100)->nullable();
            $table->string('kontak_telepon', 50)->nullable();
            $table->text('alamat_kantor')->nullable();
            $table->string('jam_layanan', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sistem');
    }
};
