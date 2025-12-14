<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'notifiable_type',
        'notifiable_id',
        'action_url',
        'is_read',
        'read_at',
        'channels',
        'channel_status',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'channels' => 'array',
            'channel_status' => 'array',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    // ==================== METHODS ====================

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    // ==================== ACCESSORS ====================

    public function getIconAttribute(): string
    {
        return match (true) {
            str_contains($this->type, 'attendance') => 'clock',
            str_contains($this->type, 'message') => 'mail',
            str_contains($this->type, 'assessment') => 'chart-bar',
            str_contains($this->type, 'activity') => 'clipboard-list',
            str_contains($this->type, 'schedule') => 'calendar',
            default => 'bell',
        };
    }

    public function getIconColorAttribute(): string
    {
        return match (true) {
            str_contains($this->type, 'attendance') => 'text-blue-500',
            str_contains($this->type, 'message') => 'text-purple-500',
            str_contains($this->type, 'assessment') => 'text-green-500',
            str_contains($this->type, 'activity') => 'text-yellow-500',
            str_contains($this->type, 'schedule') => 'text-indigo-500',
            default => 'text-gray-500',
        };
    }

    // ==================== SCOPES ====================

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
