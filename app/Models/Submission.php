<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

	protected $guarded = ["id"];

	public function student_assignment(){
		return $this->belongsTo(StudentAssignment::class);
	}
}
