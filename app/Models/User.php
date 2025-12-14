<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nis',
        'nisn',
        'role',
        'phone',
        'address',
        'birth_date',
        'gender',
        'institution',
        'department',
        'pkl_start_date',
        'pkl_end_date',
        'profile_photo',
        'is_active',
        'failed_login_attempts',
        'locked_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'pkl_start_date' => 'date',
            'pkl_end_date' => 'date',
            'is_active' => 'boolean',
            'locked_until' => 'datetime',
        ];
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a member
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Check if user account is locked
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Get the profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            // If it's already a full URL (Cloudinary), return as-is
            if (str_starts_with($this->profile_photo, 'http')) {
                return $this->profile_photo;
            }
            // Otherwise, assume it's a local storage path
            return asset('storage/' . $this->profile_photo);
        }
        
        // Default avatar with initials
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&color=3b82f6&background=e0e7ff&size=128";
    }

    // ==================== RELATIONSHIPS ====================

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function activityPlans(): HasMany
    {
        return $this->hasMany(ActivityPlan::class);
    }

    public function ideasNotes(): HasMany
    {
        return $this->hasMany(IdeaNote::class);
    }

    public function problemReports(): HasMany
    {
        return $this->hasMany(ProblemReport::class);
    }

    public function shiftSchedules(): HasMany
    {
        return $this->hasMany(ShiftSchedule::class);
    }

    public function performanceAssessments(): HasMany
    {
        return $this->hasMany(PerformanceAssessment::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // ==================== SCOPES ====================

    public function scopeMembers($query)
    {
        return $query->where('role', 'member');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
