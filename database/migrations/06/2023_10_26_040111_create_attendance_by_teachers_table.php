<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('attendance_by_teachers', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('teacher_id');
			$table->unsignedBigInteger('course_id');
			$table->unsignedBigInteger('student_id');
			$table->unsignedBigInteger('schedule_id');
			$table->string('status');
			$table->foreign('schedule_id')->references('id')->on('course_schedules');
			$table->foreign('teacher_id')->references('id')->on('users');
			$table->foreign('student_id')->references('id')->on('users');
			$table->foreign('course_id')->references('id')->on('courses');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('attendance_by_teachers');
	}
};
