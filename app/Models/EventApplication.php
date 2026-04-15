<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;
use App\Models\EventApplicationStatusHistory;
use Illuminate\Database\Eloquent\Model;

class EventApplication extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_WAITLISTED = 'waitlisted';

    protected $fillable = [
        'event_id',
        'user_id',
        'message',
        'cv_path',
        'status',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isWaitlisted(): bool
    {
        return $this->status === self::STATUS_WAITLISTED;
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'emerald-500',
            self::STATUS_REJECTED => 'red-500',
            self::STATUS_PENDING => 'amber-500',
            self::STATUS_CANCELLED => 'gray-500',
            self::STATUS_WAITLISTED => 'blue-500',
            default => 'gray-500',
        };
    }

    public function statusHistory()
    {
        return $this->hasMany(EventApplicationStatusHistory::class);
    }

    protected static function booted()
    {
        static::created(function ($application) {
            EventApplicationStatusHistory::create([
                'event_application_id' => $application->id,
                'status' => $application->status,
                'changed_by' => $application->user_id,
            ]);
        });

        static::updated(function ($application) {
            if ($application->isDirty('status')) {
                EventApplicationStatusHistory::create([
                    'event_application_id' => $application->id,
                    'status' => $application->status,
                    'changed_by' => Auth::id(), // organizer usually
                ]);
            }
        });
    }
}