<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model {
	use HasFactory;

	protected $fillable = [
		'course_topic_id',
		'title',
		'link'
	];

	public function course_topic() {
		return $this->belongsTo(CourseTopic::class, "course_topic_id");
	}

	public function progress() {
		return $this->hasMany(MaterialProgress::class);
	}
}
