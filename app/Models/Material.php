<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model {
	use HasFactory;

	protected $fillable = [
		'topic_id',
		'title',
		'link'
	];

	public function topic() {
		return $this->belongsTo(Topic::class, "topic_id");
	}

	public function progress() {
		return $this->hasMany(Progress::class);
	}
}
