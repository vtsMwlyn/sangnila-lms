<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

	protected $guarded = ["id"];

	public function receivers(){
		return $this->belongsToMany(User::class, "announcement_users");
	}
}
