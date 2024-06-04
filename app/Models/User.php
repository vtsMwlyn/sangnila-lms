<?php

namespace App\Models;

use id;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail{
	use HasApiTokens, HasFactory, Notifiable;

	protected $fillable = [
		'full_name',
		'email',
		'password',
		'role_id',
		'email_verified_at',
		"status"
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
	];

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
		return $this->belongsToMany(Course::class, 'course_students');
	}

	public function progress() {
		return $this->hasMany(MaterialProgress::class, 'student_id');
	}

	public function schedules() {
		return $this->hasMany(StudentSchedule::class, 'student_id');
	}

	public function attendances(){
		return $this->hasMany(Attendance::class, "student_attendances");
	}

	public function assignments(){
		return $this->hasMany(Assignment::class, "student_assignments");
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

}
