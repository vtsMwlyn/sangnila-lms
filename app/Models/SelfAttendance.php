<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfAttendance extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	public function user(){
		return $this->belongsTo(User::class);
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
