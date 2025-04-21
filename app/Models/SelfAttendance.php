<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfAttendance extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	public function user(){
		return $this->belongsTo(User::class);
	}

	public function course(){
		return $this->belongsTo(Course::class);
	}

	public function scopeFilter($query, array $filters){
		$query->when($filters["teacher"] ?? false, function($query, $teacher){
			return $query->whereHas("user", function($query) use ($teacher){
				return $query->where('role_id', 2)->where("full_name", "like", "%" . $teacher . "%");
			});
		});

		$query->when($filters["course"] ?? false, function($query, $course){
			return $query->whereHas("course", function($query) use ($course){
				return $query->where("course_name", "like", "%" . $course . "%");
			});
		});
	}
}
