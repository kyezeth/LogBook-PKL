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
        Schema::create('performance_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assessed_by')->constrained('users')->onDelete('cascade');
            
            // Assessment period
            $table->date('period_start');
            $table->date('period_end');
            $table->string('period_label')->nullable(); // e.g., 'December 2024', 'Week 1'
            
            // Scoring (1-5 scale or 1-100)
            $table->decimal('attendance_score', 5, 2)->nullable();
            $table->decimal('performance_score', 5, 2)->nullable();
            $table->decimal('attitude_score', 5, 2)->nullable();
            $table->decimal('teamwork_score', 5, 2)->nullable();
            $table->decimal('initiative_score', 5, 2)->nullable();
            $table->decimal('communication_score', 5, 2)->nullable();
            
            // Overall
            $table->decimal('overall_score', 5, 2)->nullable();
            $table->enum('grade', ['A', 'B', 'C', 'D', 'E'])->nullable();
            
            // Feedback
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('supervisor_comments')->nullable();
            $table->text('goals_for_next_period')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'published', 'acknowledged'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->text('student_response')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'period_start', 'period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_assessments');
    }
};
