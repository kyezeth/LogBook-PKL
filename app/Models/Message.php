<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'type',
        'parent_id',
        'conversation_id',
        'subject',
        'body',
        'attachments',
        'is_read',
        'read_at',
        'is_starred',
        'is_archived',
        'is_pinned',
        'expires_at',
        'deleted_by_sender_at',
        'deleted_by_recipient_at',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'is_starred' => 'boolean',
            'is_archived' => 'boolean',
            'is_pinned' => 'boolean',
            'expires_at' => 'datetime',
            'deleted_by_sender_at' => 'datetime',
            'deleted_by_recipient_at' => 'datetime',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    public function conversation(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'conversation_id');
    }

    // ==================== ACCESSORS ====================

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'private' => 'Pesan Pribadi',
            'announcement' => 'Pengumuman',
            'system' => 'Sistem',
            default => $this->type,
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'private' => 'bg-blue-100 text-blue-800',
            'announcement' => 'bg-purple-100 text-purple-800',
            'system' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getExcerptAttribute(): string
    {
        return \Illuminate\Support\Str::limit(strip_tags($this->body), 100);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    // ==================== SCOPES ====================

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopePrivate($query)
    {
        return $query->where('type', 'private');
    }

    public function scopeAnnouncements($query)
    {
        return $query->where('type', 'announcement');
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeForRecipient($query, $userId)
    {
        return $query->where('recipient_id', $userId)
                     ->whereNull('deleted_by_recipient_at');
    }

    public function scopeFromSender($query, $userId)
    {
        return $query->where('sender_id', $userId)
                     ->whereNull('deleted_by_sender_at');
    }
}
