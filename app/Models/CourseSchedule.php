<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model {
	use HasFactory;

	protected $fillable = [
		'course_id',
		'start_time',
		'end_time',
		'day_of_week'
	];

	public function students() {
		return $this->belongsToMany(StudentSchedule::class, 'student_schedule');
	}

	public function course() {
		return $this->belongsTo(Course::class);
	}
}
