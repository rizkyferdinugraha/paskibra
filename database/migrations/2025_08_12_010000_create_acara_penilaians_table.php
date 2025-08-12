<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acara_penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acara_id')->constrained('acaras')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('graded_by')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('fisik');
            $table->unsignedTinyInteger('kepedulian');
            $table->unsignedTinyInteger('tanggung_jawab');
            $table->unsignedTinyInteger('disiplin');
            $table->unsignedTinyInteger('kerjasama');
            $table->timestamps();
            $table->unique(['acara_id', 'user_id', 'graded_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acara_penilaians');
    }
};


