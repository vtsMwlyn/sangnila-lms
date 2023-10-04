<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTeacher extends Model {
	use HasFactory;
	protected $fillable = [
		'user_id',
		'course_id',
	];

	public function teacher() {
		return $this->belongsTo(User::class);
	}

	public function course() {
		return $this->belongsTo(Course::class);
	}
}
