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
        Schema::create('member_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('biodata_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('action', ['pending', 'approved', 'rejected', 'activated', 'deactivated']);
            $table->enum('status', ['active', 'inactive', 'rejected', 'pending'])->default('pending');
            $table->text('reason')->nullable(); // Alasan reject/deactivate
            $table->string('admin_name')->nullable(); // Nama admin yang melakukan action
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('metadata')->nullable(); // Data tambahan jika diperlukan
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_status_logs');
    }
};
