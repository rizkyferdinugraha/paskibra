<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('acaras', function (Blueprint $table) {
            $table->boolean('selesai')->default(false)->after('perlengkapan');
        });
    }

    public function down(): void
    {
        Schema::table('acaras', function (Blueprint $table) {
            $table->dropColumn('selesai');
        });
    }
};


