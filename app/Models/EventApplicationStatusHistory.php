<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventApplicationStatusHistory extends Model
{
    protected $fillable = [
        'event_application_id',
        'status',
        'changed_at',
    ];

    public function eventApplication()
    {
        return $this->belongsTo(EventApplication::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
