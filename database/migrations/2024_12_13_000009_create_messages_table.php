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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Sender and recipient
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->nullable()->constrained('users')->onDelete('cascade');
            
            // Message type
            $table->enum('type', ['private', 'announcement', 'system'])->default('private');
            
            // For threaded conversations
            $table->foreignId('parent_id')->nullable()->constrained('messages')->onDelete('cascade');
            $table->foreignId('conversation_id')->nullable(); // Groups related messages
            
            // Content
            $table->string('subject')->nullable();
            $table->text('body');
            $table->json('attachments')->nullable();
            
            // Status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_starred')->default(false);
            $table->boolean('is_archived')->default(false);
            
            // For announcements
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('expires_at')->nullable();
            
            // Soft delete for recipients
            $table->timestamp('deleted_by_sender_at')->nullable();
            $table->timestamp('deleted_by_recipient_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['recipient_id', 'is_read']);
            $table->index(['sender_id', 'type']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
