<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model {
	use HasFactory;

	protected $guarded = ["id"];

	public function topic() {
		return $this->belongsTo(Topic::class, "topic_id");
	}

	public function progress() {
		return $this->hasMany(Progress::class);
	}

	public function learning_outcomes(){
		return $this->belongsToMany(LearningOutcome::class);
	}
}
