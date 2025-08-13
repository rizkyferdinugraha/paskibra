<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->text('follow_up')->nullable()->after('status');
            $table->foreignId('follow_up_by_user_id')->nullable()->after('follow_up')->constrained('users')->nullOnDelete();
            $table->timestamp('follow_up_at')->nullable()->after('follow_up_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('follow_up');
            $table->dropConstrainedForeignId('follow_up_by_user_id');
            $table->dropColumn('follow_up_at');
        });
    }
};


