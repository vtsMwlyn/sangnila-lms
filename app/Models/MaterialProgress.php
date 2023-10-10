<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialProgress extends Model {
	use HasFactory;
	protected $fillable = [
		'student_id',
		'material_id',
		'teacher_id',
		'course_id',
		'status',
		'note '
	];

	public function teacher() {
		return $this->belongsTo(User::class, 'teacher_id');
	}

	public function student() {
		return $this->belongsTo(User::class, 'student_id');
	}

	public function course() {
		return $this->belongsTo(Course::class, 'course_id');
	}

	public function material() {
		return $this->belongsTo(CourseMaterial::class, 'material_id');
	}

}
