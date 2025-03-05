<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

	protected $guarded = ["id"];

	public function course(){
		return $this->belongsTo(Course::class, "course_id");
	}

	public function activities(){
		return $this->hasMany(Activity::class);
	}

	public function uploader(){
		return $this->belongsTo(User::class);
	}
}
