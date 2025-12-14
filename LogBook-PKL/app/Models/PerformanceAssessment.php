<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assessed_by',
        'period_start',
        'period_end',
        'period_label',
        'attendance_score',
        'performance_score',
        'attitude_score',
        'teamwork_score',
        'initiative_score',
        'communication_score',
        'overall_score',
        'grade',
        'strengths',
        'areas_for_improvement',
        'supervisor_comments',
        'goals_for_next_period',
        'status',
        'published_at',
        'acknowledged_at',
        'student_response',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'attendance_score' => 'decimal:2',
            'performance_score' => 'decimal:2',
            'attitude_score' => 'decimal:2',
            'teamwork_score' => 'decimal:2',
            'initiative_score' => 'decimal:2',
            'communication_score' => 'decimal:2',
            'overall_score' => 'decimal:2',
            'published_at' => 'datetime',
            'acknowledged_at' => 'datetime',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    // ==================== ACCESSORS ====================

    public function getGradeBadgeClassAttribute(): string
    {
        return match ($this->grade) {
            'A' => 'bg-green-100 text-green-800',
            'B' => 'bg-blue-100 text-blue-800',
            'C' => 'bg-yellow-100 text-yellow-800',
            'D' => 'bg-orange-100 text-orange-800',
            'E' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'published' => 'bg-blue-100 text-blue-800',
            'acknowledged' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPeriodAttribute(): string
    {
        if ($this->period_label) {
            return $this->period_label;
        }

        return $this->period_start->format('d M') . ' - ' . $this->period_end->format('d M Y');
    }

    /**
     * Calculate overall score from individual scores
     */
    public function calculateOverallScore(): float
    {
        $scores = [
            $this->attendance_score,
            $this->performance_score,
            $this->attitude_score,
            $this->teamwork_score,
            $this->initiative_score,
            $this->communication_score,
        ];

        $validScores = array_filter($scores, fn($s) => $s !== null);
        
        if (empty($validScores)) {
            return 0;
        }

        return array_sum($validScores) / count($validScores);
    }

    /**
     * Determine grade based on overall score
     */
    public function calculateGrade(): string
    {
        $score = $this->overall_score ?? $this->calculateOverallScore();

        return match (true) {
            $score >= 90 => 'A',
            $score >= 80 => 'B',
            $score >= 70 => 'C',
            $score >= 60 => 'D',
            default => 'E',
        };
    }

    // ==================== SCOPES ====================

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->orWhere('status', 'acknowledged');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('period_end', 'desc');
    }
}
