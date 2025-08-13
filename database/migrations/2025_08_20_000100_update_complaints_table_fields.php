<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            if (!Schema::hasColumn('complaints', 'terlapor_user_id')) {
                $table->foreignId('terlapor_user_id')->nullable()->after('deskripsi')->constrained('users')->cascadeOnDelete();
            }
            if (Schema::hasColumn('complaints', 'kontak')) {
                $table->dropColumn('kontak');
            }
            if (Schema::hasColumn('complaints', 'waktu_kejadian')) {
                $table->dropColumn('waktu_kejadian');
            }
            if (Schema::hasColumn('complaints', 'lokasi')) {
                $table->dropColumn('lokasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            if (Schema::hasColumn('complaints', 'terlapor_user_id')) {
                $table->dropConstrainedForeignId('terlapor_user_id');
            }
            if (!Schema::hasColumn('complaints', 'kontak')) {
                $table->string('kontak')->nullable();
            }
            if (!Schema::hasColumn('complaints', 'waktu_kejadian')) {
                $table->dateTime('waktu_kejadian')->nullable();
            }
            if (!Schema::hasColumn('complaints', 'lokasi')) {
                $table->string('lokasi')->nullable();
            }
        });
    }
};


