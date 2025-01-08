<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerAttendance extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	public function teacher(){
		return $this->belongsTo(User::class, 'user_id');
	}

	public function course(){
		return $this->belongsTo(Course::class);
	}

	public function scopeFilter($query, array $filters){
		$query->when($filters["search"] ?? false, function($query, $search){
			return $query->whereHas("teacher", function($query) use ($search){
				return $query->where("full_name", "like", "%" . $search . "%");
			});
		});
	}
}
