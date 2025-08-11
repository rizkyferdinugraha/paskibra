<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acaras', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->dateTime('tanggal');
            $table->string('lokasi')->nullable();
            $table->string('seragam')->nullable();
            $table->json('perlengkapan')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('acara_wajib_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acara_id')->constrained('acaras')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['acara_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acara_wajib_users');
        Schema::dropIfExists('acaras');
    }
};


