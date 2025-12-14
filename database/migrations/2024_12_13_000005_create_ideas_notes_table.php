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
        Schema::create('ideas_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Content
            $table->enum('type', ['idea', 'suggestion', 'note'])->default('note');
            $table->string('title');
            $table->text('content');
            
            // Categorization
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            
            // Status for ideas/suggestions
            $table->enum('status', ['draft', 'submitted', 'under_review', 'accepted', 'implemented', 'rejected'])->default('submitted');
            
            // Admin response
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_response')->nullable();
            
            // Visibility
            $table->boolean('is_public')->default(false); // visible to other students
            
            $table->timestamps();
            
            // Indexes
            $table->index('type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ideas_notes');
    }
};
