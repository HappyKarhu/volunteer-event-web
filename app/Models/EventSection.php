<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'role_name',
        'capacity',
        'description',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function volunteers()
    {
        return $this->hasMany(SectionVolunteer::class, 'event_section_id');
    }
}
