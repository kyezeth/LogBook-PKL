<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdeaNote extends Model
{
    use HasFactory;

    protected $table = 'ideas_notes';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'content',
        'category',
        'tags',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_response',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'reviewed_at' => 'datetime',
            'is_public' => 'boolean',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ==================== ACCESSORS ====================

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'idea' => 'Ide',
            'suggestion' => 'Saran',
            'note' => 'Catatan',
            default => $this->type,
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'idea' => 'bg-purple-100 text-purple-800',
            'suggestion' => 'bg-blue-100 text-blue-800',
            'note' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'under_review' => 'bg-yellow-100 text-yellow-800',
            'accepted' => 'bg-green-100 text-green-800',
            'implemented' => 'bg-emerald-100 text-emerald-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // ==================== SCOPES ====================

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['submitted', 'under_review']);
    }
}
