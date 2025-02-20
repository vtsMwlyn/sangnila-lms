<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail{
	use HasApiTokens, HasFactory, Notifiable;

	protected $guarded = ["id"];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
	];


	// Query Scopes
	public function scopeFilter($query, array $filters){
		$query->when($filters["search"] ?? false, function($query, $search){
			return $query->where("full_name", "like", "%" . $search . "%");
		});

		$query->when($filters["role"] ?? false, function($query, $role){
			if($role == 'Disabled'){
				return $query->where("status", $role);
			}
			else {
				return $query->whereHas("role", function($query) use($role){
					$query->where("role_name", $role);
				});
			}

		});

		$query->when($filters['course'] ?? false, function ($query, $course) {
            return $query->whereHas('enrolled_courses', function($query) use ($course){
				return $query->where('course_id', $course);
			});
        });
	}


	// Relationships
	public function details() {
		return $this->hasOne(UserDetail::class);
	}

	public function role() {
		return $this->belongsTo(Role::class);
	}

	public function teached_courses() {
		return $this->belongsToMany(Course::class, 'course_teachers');
	}

	public function enrolled_courses() {
		return $this->belongsToMany(Course::class, 'course_students', "student_id", "course_id");
	}

	public function course_students(){
		return $this->hasMany(CourseStudent::class, 'student_id');
	}

	public function student_attendances(){
		return $this->hasMany(StudentAttendance::class, 'student_id');
	}

	public function progress() {
		return $this->hasMany(Progress::class, 'student_id');
	}

	public function schedules() {
		return $this->hasMany(StudentSchedule::class, 'student_id');
	}

	public function attendances(){
		return $this->belongsToMany(Attendance::class, "student_attendances", "student_id");
	}

	public function assignments(){
		return $this->hasMany(Assignment::class, "student_assignments", "student_id");
	}

	public function posted_attendances(){
		return $this->hasMany(Attendance::class);
	}

	public function posted_assignments(){
		return $this->hasMany(Assignment::class);
	}

	public function submissions(){
		return $this->hasMany(Submission::class, "student_id");
	}

	public function payments(){
		return $this->belongsToMany(Course::class, "payments");
	}

	public function inboxes(){
		return $this->hasMany(Notification::class);
	}
}
