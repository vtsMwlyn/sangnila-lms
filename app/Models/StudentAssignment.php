<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssignment extends Model
{
    use HasFactory;

	protected $guarded = ["id"];

	// Relationships
	public function student(){
		return $this->belongsTo(User::class, "student_id");
	}

	public function assignment(){
		return $this->belongsTo(Assignment::class);
	}
}
