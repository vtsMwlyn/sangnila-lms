<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningOutcome extends Model
{
    use HasFactory;

	protected $guarded = ["id"];

	public function course(){
		return $this->belongsTo(Course::class);
	}

	public function activities(){
		return $this->belongsToMany(LearningOutcome::class);
	}
}
