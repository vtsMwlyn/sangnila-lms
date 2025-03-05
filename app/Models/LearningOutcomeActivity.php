<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningOutcomeActivity extends Model
{
    use HasFactory;

	protected $guarded = ["id"];

	public function learning_outcome(){
		return $this->belongsTo(LearningOutcome::class);
	}

	public function activity(){
		return $this->belongsTo(Activity::class);
	}
}
