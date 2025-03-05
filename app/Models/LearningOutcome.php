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
		return $this->belongsToMany(Activity::class, "learning_outcome_activities");
	}

	public function curriculum_activities(){
		return $this->belongsToMany(CurriculumActivity::class, "learning_outcome_curriculum_activities");
	}
}
