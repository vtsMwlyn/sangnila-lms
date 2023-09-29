<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSchedule extends Model {
	use HasFactory;
	protected $fillable = [
		'schedule_id',
		'student_id',
	];

	public function schedule() {
		return $this->belongsTo(CourseSchedule::class);
	}

	public function student(){
		return $this->belongsTo(User::class);
	}

	public function attendances() {
		return $this->hasMany(StudentAttendance::class);
	}

}
