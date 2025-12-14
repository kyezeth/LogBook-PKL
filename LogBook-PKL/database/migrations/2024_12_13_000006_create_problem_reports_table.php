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
        Schema::create('problem_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Problem details
            $table->string('title');
            $table->text('description');
            $table->string('category')->nullable(); // e.g., 'technical', 'interpersonal', 'resource'
            
            // Urgency and impact
            $table->enum('urgency', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('impact', ['low', 'medium', 'high'])->default('medium');
            
            // Evidence
            $table->json('attachments')->nullable(); // screenshots, documents
            
            // Status tracking
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed', 'wont_fix'])->default('open');
            
            // Resolution
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('urgency');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problem_reports');
    }
};
