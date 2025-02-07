<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up() {
		Schema::create('attendances', function (Blueprint $table) {
			$table->id();

			$table->unsignedBigInteger('teacher_id');
			$table->unsignedBigInteger("course_id");

			// $table->unsignedBigInteger('schedule_id');
			$table->date("attendance_date");
			// $table->string("attendance_identifier");

			// $table->foreign('schedule_id')->references('id')->on('course_schedules');
			$table->foreign('teacher_id')->references('id')->on('users')->onDelete("cascade");
			$table->foreign('course_id')->references('id')->on('courses')->onDelete("cascade");

			$table->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists('attendances');
	}
};
