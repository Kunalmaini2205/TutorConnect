<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'grade_level',
        'learning_goals'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function learningProgressLogs()
    {
        return $this->hasMany(LearningProgress::class);
    }

    public function favoriteTutors()
    {
        return $this->belongsToMany(Tutor::class, 'favorites');
    }
}
