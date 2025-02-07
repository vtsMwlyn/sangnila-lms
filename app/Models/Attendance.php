<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model {
	use HasFactory;

	protected $guarded = ["id"];

	// Relationships
	public function course(){
		return $this->belongsTo(Course::class);
	}

	public function posted_by(){
		return $this->belongsTo(User::class, "uploader_id");
	}

	public function students(){
		return $this->belongsToMany(User::class, "student_attendances", "user_id");
	}

	public function schedule() {
		return $this->belongsTo(CourseSchedule::class, 'schedule_id');
	}

	public function student_attendances(){
		return $this->hasMany(StudentAttendance::class);
	}

}
