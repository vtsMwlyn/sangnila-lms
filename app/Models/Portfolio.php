<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function student(){
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    // Query scope
	public function scopeFilter($query, array $filters)
    {
        $query->when($filters['teacher'] ?? false, function ($query, $teacher) {
            $query->whereHas('course.course_students', function ($q) use ($teacher) {
                $q->whereHas('teacher', function ($q2) use ($teacher) {
                    $q2->where('full_name', 'like', '%' . $teacher . '%');
                });
            });
        });

        $query->when($filters['student'] ?? false, function ($query, $student) {
            $query->whereHas('student', function ($q) use ($student) {
                $q->where('full_name', 'like', '%' . $student . '%');
            });
        });
    }



}
