<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventSection;

class SectionVolunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_section_id',
        'user_id',
        'joined_at',
    ];

    public function section()
    {
        return $this->belongsTo(EventSection::class, 'event_section_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}