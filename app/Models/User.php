<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\EventApplication;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'contact_email',
        // Organizer-specific
        'company_name',
        'phone',
        'website',
        'logo',
        // Volunteer-specific
        'bio',
        'skills',
        'avatar',
    ];

    /**
     * Hidden attributes for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationship: events created by organizer
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    /**
     * Helper method: check if user is organizer
     */
    public function isOrganizer(): bool
    {
        return $this->role === 'organizer';
    }

    /**
     * Helper method: check if user is volunteer
     */
    public function isVolunteer(): bool
    {
        return $this->role === 'volunteer';
    }

    /**
     * Relationship: events the volunteer is attending
     */
    public function eventAttendances()
    {
        return $this->hasMany(EventAttendee::class);
    }

    public function savedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_user_saves')
                    ->withTimestamps();
    }
    public function attendedEvents()
    {
        return $this->hasManyThrough(
            Event::class,
            EventAttendee::class,
            'user_id',     // Foreign key on EventAttendee
            'id',          // Foreign key on Event
            'id',          // Local key on User
            'event_id'     // Local key on EventAttendee
        );
    }
    

    public function appliedEvents()
    {
        return $this->hasManyThrough(
            Event::class,
            EventApplication::class,
            'user_id',
            'id',
            'id',
            'event_id'
        );
    }

    /**
     * Get the organizer logo URL, or default if missing
     */
    public function getLogoUrlAttribute(): string
    {
        // If logo exists, use storage path; otherwise default-logo.png
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }

        return asset('images/logos/default-logo.png');
    }

    /**
     * Get the volunteer avatar URL, or default if missing
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return asset('images/avatars/default-avatar.png');
    }

    /**
     * Unified accessor for profile image
     */
    public function getProfileImageUrlAttribute(): string
    {
        return $this->isOrganizer() ? $this->logo_url : $this->avatar_url;
    }

     /**
     * Applications relationship for volunteers applying to events
     */
    public function applications()
    {
        return $this->hasMany(EventApplication::class);
    }
}