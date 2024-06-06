<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up() {
		Schema::create('course_teachers', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('course_id');
			$table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
			$table->foreign('course_id')->references('id')->on('courses')->onDelete("cascade");
			$table->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists('course_teachers');
	}
};
