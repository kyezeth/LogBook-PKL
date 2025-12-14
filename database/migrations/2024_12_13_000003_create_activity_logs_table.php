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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            
            // Activity details
            $table->string('title');
            $table->text('description');
            $table->string('category')->nullable(); // e.g., 'development', 'meeting', 'documentation'
            
            // Time tracking
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('duration_minutes')->nullable(); // calculated from start/end
            
            // Attachments
            $table->json('images')->nullable(); // array of image paths
            $table->json('attachments')->nullable(); // array of file paths
            
            // Status
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'approved', 'rejected'])->default('submitted');
            
            // Supervisor review
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->integer('rating')->nullable(); // 1-5 rating by supervisor
            
            $table->timestamps();
            
            // Indexes
            $table->index('date');
            $table->index('category');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
