<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningProgress extends Model
{
    use HasFactory;

    protected $table = 'learning_progress';

    protected $fillable = [
        'student_id',
        'tutor_id',
        'subject_id',
        'notes',
        'progress_percentage',
        'recorded_date'
    ];

    protected $casts = [
        'recorded_date' => 'date',
        'progress_percentage' => 'integer'
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
}
