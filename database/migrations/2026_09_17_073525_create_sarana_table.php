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
        Schema::create('sarana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_sarana')->cascadeOnDelete();
            $table->string('nama_sarana', 150);
            $table->string('slug', 180)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('lokasi', 255);
            $table->decimal('harga_per_slot', 10, 2)->default(0);
            $table->time('jam_buka')->default('06:00:00');
            $table->time('jam_tutup')->default('22:00:00');
            $table->integer('interval_slot_menit')->default(60);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarana');
    }
};
