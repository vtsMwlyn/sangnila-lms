<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model {
    use HasFactory;

	protected $guarded = ["id"];

	// Relationships
	public function courses(){
		return $this->belongsToMany(Course::class, "student_assignments");
	}

	public function posted_by(){
		return $this->belongsTo(User::class, "teacher_id");
	}

	public function students(){
		return $this->belongsToMany(User::class, "student_assignments");
	}

	public function course(){
		return $this->belongsTo(Course::class);
	}

	public function student_assignments(){
		return $this->hasMany(StudentAssignment::class);
	}
}
