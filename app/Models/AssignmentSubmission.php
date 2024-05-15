<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model {
    use HasFactory;

	protected $guarded = ["id"];

	public function assignment(){
		return $this->belongsTo(StudentAssignment::class, "assignment_id");
	}

	public function uploaded_by(){
		return $this->belongsTo(User::class, "student_id");
	}
}
