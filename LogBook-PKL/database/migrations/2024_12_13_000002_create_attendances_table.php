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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            
            // Check-in data
            $table->time('check_in_time')->nullable();
            $table->string('check_in_photo')->nullable();
            $table->decimal('check_in_latitude', 10, 8)->nullable();
            $table->decimal('check_in_longitude', 11, 8)->nullable();
            
            // Check-out data
            $table->time('check_out_time')->nullable();
            $table->string('check_out_photo')->nullable();
            $table->decimal('check_out_latitude', 10, 8)->nullable();
            $table->decimal('check_out_longitude', 11, 8)->nullable();
            
            // Status and notes
            $table->enum('status', ['present', 'late', 'absent', 'leave', 'sick'])->default('absent');
            $table->text('notes')->nullable();
            
            // Admin verification
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
            
            // Unique constraint - one attendance per user per day
            $table->unique(['user_id', 'date']);
            
            // Indexes
            $table->index('date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
