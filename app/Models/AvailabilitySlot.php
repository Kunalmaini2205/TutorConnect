<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailabilitySlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutor_id',
        'date',
        'start_time',
        'end_time',
        'is_booked'
    ];

    protected $casts = [
        'date' => 'date',
        'is_booked' => 'boolean'
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class, 'slot_id');
    }
}
