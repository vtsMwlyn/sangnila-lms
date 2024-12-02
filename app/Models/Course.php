<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model {
	use HasFactory;

	protected $guarded = ["id"];

	// Query scope
	public function scopeFilter($query, array $filters){
		$query->when($filters["search"] ?? false, function($query, $search){
			return $query->where(function($query) use($search){
				$query->where("course_name", "like", "%" . $search . "%");
			});
		});
	}

	// Relationships
	public function topics(){
		return $this->hasMany(Topic::class);
	}

	public function teachers() {
		return $this->belongsToMany(User::class, "course_teachers");
	}

	public function students() {
		return $this->belongsToMany(User::class, "course_students", "course_id", "student_id");
	}

	public function course_students(){
		return $this->hasMany(CourseStudent::class);
	}

	public function schedules() {
		return $this->hasMany(CourseSchedule::class);
	}

	public function attendances(){
		return $this->hasMany(Attendance::class);
	}

	public function assignments(){
		return $this->hasMany(Assignment::class);
	}

	public function students_paid(){
		return $this->belongsToMany(User::class, "payments", "student_id");
	}

	public function curriculum_topics(){
		return $this->hasMany(CurriculumTopic::class);
	}

	public function learning_outcomes(){
		return $this->hasMany(LearningOutcome::class);
	}

}


