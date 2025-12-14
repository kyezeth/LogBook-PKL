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
        Schema::create('shift_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Schedule details
            $table->date('date');
            $table->string('shift_name'); // e.g., 'Morning', 'Afternoon', 'Full Day'
            $table->time('start_time');
            $table->time('end_time');
            
            // Visual styling
            $table->string('color', 7)->default('#3b82f6'); // hex color for calendar display
            
            // Notes
            $table->text('notes')->nullable();
            
            // Admin who created/modified
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            
            // Indexes
            $table->index('date');
            $table->index(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_schedules');
    }
};
