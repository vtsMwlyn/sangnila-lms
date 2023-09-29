<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model {
	use HasFactory;
	protected $fillable = [
		'student_schedule_id',
		'status',
		'reason',
		'submit_date',
		'submit_time',
		'validated_by_teacher'
	];

	public function teacher(){
		return $this->belongsTo(User::class);
	}

	public function student_schedule(){
		return $this->belongsTo(StudentSchedule::class);
	}
}
