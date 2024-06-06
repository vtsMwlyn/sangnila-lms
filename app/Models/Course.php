<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model {
	use HasFactory;

	protected $fillable = [
		'course_name',
		'course_description',
		'visibility'
	];

	public function course_topics(){
		return $this->hasMany(CourseTopic::class);
	}

	public function teachers() {
		return $this->belongsToMany(User::class, "course_teachers");
	}

	public function students() {
		return $this->belongsToMany(User::class, "course_students", "course_id", "student_id");
	}

	public function course_students(){
		return $this->hasMany(CourseStudent::class);
	}

	public function schedules() {
		return $this->hasMany(CourseSchedule::class);
	}

	public function attendances(){
		return $this->hasMany(Attendance::class);
	}

	public function assignments(){
		return $this->hasMany(Assignment::class);
	}

}


