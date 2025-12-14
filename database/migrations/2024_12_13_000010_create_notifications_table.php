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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Notification content
            $table->string('type'); // e.g., 'attendance.reminder', 'message.received', 'assessment.published'
            $table->string('title');
            $table->text('body');
            
            // Related entity
            $table->string('notifiable_type')->nullable(); // Model class
            $table->unsignedBigInteger('notifiable_id')->nullable(); // Model ID
            
            // Action URL
            $table->string('action_url')->nullable();
            
            // Status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            
            // Delivery channels
            $table->json('channels')->nullable(); // ['database', 'email', 'sms']
            $table->json('channel_status')->nullable(); // Track delivery per channel
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'is_read']);
            $table->index('type');
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
