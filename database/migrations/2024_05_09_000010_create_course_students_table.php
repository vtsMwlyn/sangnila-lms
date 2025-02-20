<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up() {
		Schema::create('course_students', function (Blueprint $table) {
			$table->id();

			$table->unsignedBigInteger('student_id');
			$table->unsignedBigInteger('course_id');
			$table->unsignedBigInteger("teacher_id");
			$table->enum('student_type', ['regular', 'private']);
			$table->unsignedInteger("is_imported");

			$table->unsignedInteger("max_course_session");
			$table->enum('learning_status', ['learning', 'complete', 'undone'])->default('learning');
			$table->unsignedInteger('temp_periods_paid');
			$table->unsignedInteger('enable_temp_periods_paid');

			$table->foreign('student_id')->references('id')->on('users')->onDelete("cascade");
			$table->foreign('course_id')->references('id')->on('courses')->onDelete("cascade");
			$table->foreign('teacher_id')->references('id')->on('users')->onDelete("cascade");

			$table->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists('course_students');
	}
};
