<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail{
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'full_name',
		'email',
		'password',
		'role_id',
		'email_verified_at',
		"status"
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

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

	public function course_attendances(){
		return $this->hasMany(Attendance::class, "student_id");
	}

	public function submitted_attendances(){
		return $this->hasMany(Attendance::class, "teacher_id");
	}

	public function assigned_assignments(){
		return $this->hasMany(Assignment::class, "student_id");
	}

	public function course_assignments(){
		return $this->hasMany(Assignment::class, "teacher_id");
	}

	public function assignment_submissions(){
		return $this->hasMany(AssignmentSubmission::class, "student_id");
	}

}
