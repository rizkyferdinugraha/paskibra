<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelapor');
            $table->string('judul');
            $table->text('deskripsi');
            $table->foreignId('terlapor_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('bukti_path')->nullable();
            $table->string('status')->default('baru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};


