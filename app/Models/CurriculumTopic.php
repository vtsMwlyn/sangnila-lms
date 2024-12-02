<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumTopic extends Model
{
    use HasFactory;

	protected $guarded = ["id"];

	public function course(){
		return $this->belongsTo(Course::class);
	}

	public function curriculum_activities(){
		return $this->hasMany(CurriculumActivity::class);
	}
}
