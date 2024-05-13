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
		return $this->belongsToMany(User::class, 'course_teachers');
	}

	public function students() {
		return $this->belongsToMany(User::class, 'course_students');
	}

	public function schedules() {
		return $this->hasMany(CourseSchedule::class);
	}

	# SOON (Portfolios)

	public function attendances() {
		return $this->hasMany(StudentAttendance::class, 'course_id');
	}

	public function assignments(){
		return $this->hasMany(StudentAssignment::class, "course_id");
	}

}


