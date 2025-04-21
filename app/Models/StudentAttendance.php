<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

	protected $guarded = ["id"];

	// Relationships
	public function student(){
		return $this->belongsTo(User::class, "student_id");
	}

	public function course(){
		return $this->belongsTo(Course::class);
	}

	public function attendance(){
		return $this->belongsTo(Attendance::class);
	}

	public function scopeFilter($query, array $filters){
		$query->when($filters["student"] ?? false, function($query, $student){
			return $query->whereHas("student", function($query) use ($student){
				return $query->where("full_name", "like", "%" . $student . "%");
			});
		});

		$query->when($filters["course"] ?? false, function($query, $course){
			return $query->whereHas('attendance', function($query) use ($course){
				return $query->whereHas("course", function($query) use ($course){
					return $query->where("course_name", "like", "%" . $course . "%");
				});
			});
		});
	}
}
