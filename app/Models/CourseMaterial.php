<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model {
	use HasFactory;
	protected $fillable = [
		'course_id',
		'title',
		'link'
	];

	public function course() {
		return $this->belongsTo(Course::class);
	}

	public function progress() {
		return $this->hasMany(MaterialProgress::class);
	}
}
