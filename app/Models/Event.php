<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\EventAttendee;
use App\Models\SectionVolunteer;
use App\Models\User;


class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'type',
        'organizer_id',
        'photo',
        'capacity',
        'location',
        'tags',
        'requirements',
        'responsibilities',
        'bring_wear',
        'is_free',
        'price',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
    ];


    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function sections()
    {
        return $this->hasMany(EventSection::class);
    }

    public function attendees()
    {
        return $this->hasMany(EventAttendee::class);
    }

    //capacity counting - attendees + volunteers, counting unique users only
public function participantCount(): int
    {
        $eventAttendees = EventAttendee::query()
            ->where('event_id', $this->id)
            ->select('user_id');

        $sectionVolunteers = SectionVolunteer::query()
            ->whereHas('section', function ($query) {
                $query->where('event_id', $this->id);
            })
            ->select('user_id');

        return DB::query()
            ->fromSub($eventAttendees->union($sectionVolunteers), 'participants')
            ->count();
    }

    public function isFull(): bool
    {
        if ($this->capacity === null) {
            return false;
        }

        return $this->participantCount() >= $this->capacity;
    }
}
