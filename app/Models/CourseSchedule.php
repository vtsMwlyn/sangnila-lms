<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model {
	use HasFactory;

	protected $guarded = ["id"];

	public function student_schedules() {
		return $this->hasMany(StudentSchedule::class, 'schedule_id');
	}

	public function course() {
		return $this->belongsTo(Course::class);
	}
}
