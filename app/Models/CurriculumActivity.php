<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumActivity extends Model
{
    use HasFactory;

	protected $guarded = ["id"];

	public function curriculum_topic(){
		return $this->belongsTo(CurriculumTopic::class);
	}
}
