<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssignment extends Model {
    use HasFactory;

	protected $guarded = ["id"];

	public function submitted_by(){
		return $this->belongsTo(User::class, "student_id");
	}

	public function posted_by(){
		return $this->belongsTo(User::class, "teacher_id");
	}

	public function course(){
		return $this->belongsTo(Course::class, "course_id");
	}

	public function submissions(){
		return $this->hasMany(AssignmentSubmission::class, "assignment_id");
	}
}
