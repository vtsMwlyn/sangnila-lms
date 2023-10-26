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

	public function materials(){
		return $this->hasMany(CourseMaterial::class);
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
		return $this->hasMany(AttendanceByTeacher::class, 'course_id');
	}

}


