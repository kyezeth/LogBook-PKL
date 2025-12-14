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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            
            // Who performed the action
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable(); // Store name in case user is deleted
            $table->string('user_role')->nullable();
            
            // What was affected
            $table->string('auditable_type'); // Model class
            $table->unsignedBigInteger('auditable_id'); // Model ID
            
            // Action details
            $table->string('action'); // 'created', 'updated', 'deleted', 'login', 'logout', etc.
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            
            // Request context
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            
            // Additional metadata
            $table->json('metadata')->nullable();
            
            $table->timestamp('created_at');
            
            // Indexes
            $table->index(['auditable_type', 'auditable_id']);
            $table->index('user_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
