<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerValidation extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	public function validator(){
		return $this->belongsTo(User::class, 'validator_id');
	}

	public function teacher(){
		return $this->belongsTo(User::class, 'teacher_id');
	}

	public function course(){
		return $this->belongsTo(Course::class);
	}
}
