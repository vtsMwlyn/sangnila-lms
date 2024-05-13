<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model {
	use HasFactory;

	protected $fillable = [
		'teacher_id',
		'student_id',
		'schedule_id',
		'course_id',
		"is_attend",
		"attendance_detail"
	];

	public function teacher(){
		return $this->belongsTo(User::class, 'teacher_id');
	}

	public function student() {
		return $this->belongsTo(User::class, 'student_id');
	}

	public function course() {
		return $this->belongsTo(Course::class, 'course_id');
	}

	public function schedule() {
		return $this->belongsTo(CourseSchedule::class, 'schedule_id');
	}

}
