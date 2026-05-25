<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug'
    ];

    public function tutors()
    {
        return $this->belongsToMany(Tutor::class, 'tutor_subjects');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function learningProgressLogs()
    {
        return $this->hasMany(LearningProgress::class);
    }
}
