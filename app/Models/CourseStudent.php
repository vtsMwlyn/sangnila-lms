<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseStudent extends Model {
	use HasFactory;
	protected $fillable = [
		'user_id',
		'course_id',
		'student_type',
		"max_course_session"
	];

	public function student() {
		return $this->belongsTo(User::class, "student_id");
	}

	public function course() {
		return $this->belongsTo(Course::class, "course_id");
	}

	public function teacher(){
		return $this->belongsTo(User::class, "teacher_id");
	}
}
