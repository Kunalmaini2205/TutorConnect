<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tutor_id',
        'subject_id',
        'slot_id',
        'date',
        'start_time',
        'end_time',
        'status', // pending, accepted, rejected, completed, cancelled
        'total_price',
        'payment_status', // unpaid, paid
        'meet_link',
        'status_notes'
    ];

    protected $casts = [
        'date' => 'date',
        'total_price' => 'decimal:2'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function availabilitySlot()
    {
        return $this->belongsTo(AvailabilitySlot::class, 'slot_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
