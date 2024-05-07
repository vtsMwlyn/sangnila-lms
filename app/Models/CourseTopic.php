<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTopic extends Model
{
    use HasFactory;

	protected $guarded = ["id"];

	public function course(){
		return $this->belongsTo(Course::class, "course_id");
	}

	public function course_materials(){
		return $this->hasMany(CourseMaterial::class);
	}
}
