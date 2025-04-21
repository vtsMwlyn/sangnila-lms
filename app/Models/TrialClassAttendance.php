<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrialClassAttendance extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function uploader(){
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function scopeFilter($query, array $filters){
		$query->when($filters["candidate"] ?? false, function($query, $candidate){
			return $query->where("candidate_name", "like", "%" . $candidate . "%");
		});

		$query->when($filters["course"] ?? false, function($query, $course){
            return $query->whereHas("course", function($query) use ($course){
                return $query->where("course_name", "like", "%" . $course . "%");
            });
		});
	}
}
