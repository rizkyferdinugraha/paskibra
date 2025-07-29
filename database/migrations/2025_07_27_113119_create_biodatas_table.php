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
        Schema::create('biodatas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('no_kta')->unique();
            $table->string('pas_foto_url');
            $table->string('nama_lengkap');
            $table->date('tanggal_lahir');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->string('jenis_kelamin');
            $table->string('no_telepon');
            $table->text('alamat');
            $table->foreignId('jurusan_id')->constrained()->onDelete('cascade');
            $table->integer('tahun_angkatan');
            $table->boolean('super_admin');
            $table->boolean('is_active');
            $table->text('riwayat_penyakit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodatas');
    }
};
