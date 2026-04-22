<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'event_application_id',
        'sender_id',
        'receiver_id',
        'message',
        'read_at',
    ];

    public function application()
    {
        return $this->belongsTo(EventApplication::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
