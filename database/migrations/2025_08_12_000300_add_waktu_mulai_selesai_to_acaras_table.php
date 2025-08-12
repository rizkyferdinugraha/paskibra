<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('acaras', function (Blueprint $table) {
            $table->dateTime('waktu_mulai')->nullable()->after('tanggal');
            $table->dateTime('waktu_selesai')->nullable()->after('waktu_mulai');
        });
    }

    public function down(): void
    {
        Schema::table('acaras', function (Blueprint $table) {
            $table->dropColumn(['waktu_mulai', 'waktu_selesai']);
        });
    }
};


