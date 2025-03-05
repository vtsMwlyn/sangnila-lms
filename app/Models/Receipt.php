<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

	public function course_student(){
		return $this->belongsTo(CourseStudent::class);
	}

	public function payment_account(){
		return $this->belongsTo(PaymentAccount::class, 'receiver_detail_id');
	}
}
