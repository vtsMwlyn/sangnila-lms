<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseStudent extends Model {
	use HasFactory;
	protected $fillable = [
		'user_id',
		'course_id',
		'student_type'
	];

	public function parent_user() {
		return $this->belongsTo(User::class);
	}

	public function parent_course() {
		return $this->belongsTo(Course::class);
	}
}
