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
        Schema::create('activity_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Plan details
            $table->string('title');
            $table->text('description');
            $table->date('planned_date');
            $table->time('planned_start_time')->nullable();
            $table->time('planned_end_time')->nullable();
            
            // Priority and category
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('category')->nullable();
            
            // Status tracking
            $table->enum('status', ['pending', 'approved', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->integer('progress_percentage')->default(0);
            
            // Approval workflow
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('planned_date');
            $table->index('status');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_plans');
    }
};
