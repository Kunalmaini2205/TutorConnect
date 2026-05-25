<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'hourly_rate',
        'bio',
        'experience',
        'qualification',
        'rating',
        'is_verified',
        'zoom_link'
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_verified' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'tutor_subjects');
    }

    public function availabilitySlots()
    {
        return $this->hasMany(AvailabilitySlot::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function uploadedMaterials()
    {
        return $this->hasMany(UploadedMaterial::class);
    }

    public function learningProgressLogs()
    {
        return $this->hasMany(LearningProgress::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(Student::class, 'favorites');
    }
}
