<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'auditable_type',
        'auditable_id',
        'action',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'metadata',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->created_at ?? now();
        });
    }

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    // ==================== STATIC METHODS ====================

    public static function log(
        string $action,
        Model $model,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?array $metadata = null
    ): self {
        $user = auth()->user();

        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_role' => $user?->role,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'metadata' => $metadata,
        ]);
    }

    // ==================== ACCESSORS ====================

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'created' => 'Dibuat',
            'updated' => 'Diperbarui',
            'deleted' => 'Dihapus',
            'login' => 'Login',
            'logout' => 'Logout',
            'failed_login' => 'Login Gagal',
            default => ucfirst($this->action),
        };
    }

    public function getActionBadgeClassAttribute(): string
    {
        return match ($this->action) {
            'created' => 'bg-green-100 text-green-800',
            'updated' => 'bg-blue-100 text-blue-800',
            'deleted' => 'bg-red-100 text-red-800',
            'login' => 'bg-purple-100 text-purple-800',
            'logout' => 'bg-gray-100 text-gray-800',
            'failed_login' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getModelNameAttribute(): string
    {
        $className = class_basename($this->auditable_type);
        
        return match ($className) {
            'User' => 'Pengguna',
            'Attendance' => 'Absensi',
            'ActivityLog' => 'Log Aktivitas',
            'ActivityPlan' => 'Rencana Aktivitas',
            'IdeaNote' => 'Ide & Catatan',
            'ProblemReport' => 'Laporan Masalah',
            'ShiftSchedule' => 'Jadwal Shift',
            'PerformanceAssessment' => 'Penilaian Kinerja',
            'Message' => 'Pesan',
            default => $className,
        };
    }

    // ==================== SCOPES ====================

    public function scopeForModel($query, Model $model)
    {
        return $query->where('auditable_type', get_class($model))
                     ->where('auditable_id', $model->getKey());
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
