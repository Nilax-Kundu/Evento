<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'date',
        'time',
        'location',
        'max_participants',
        'registration_start',
        'registration_end',
        'registration_fee',
        'image',
        'status',
    ];

    protected $casts = [
        'date'               => 'date',
        'time'               => 'datetime:H:i',
        'registration_start' => 'datetime',
        'registration_end'   => 'datetime',
        'registration_fee'   => 'decimal:2',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function getRegistrationStatusAttribute()
    {
        $now = now();
        if ($now < $this->registration_start) {
            return 'upcoming';
        } elseif ($now <= $this->registration_end) {
            return 'open';
        } else {
            return 'closed';
        }
    }
}
