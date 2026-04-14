<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventApplication extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'message',
        'cv_path',
        'status',
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
        return $this->status === null || $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
