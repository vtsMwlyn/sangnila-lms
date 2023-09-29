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
		Schema::create('student_schedules', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('schedule_id');
			$table->unsignedBigInteger('student_id');
			$table->foreign('schedule_id')->references('id')->on('course_schedules');
			$table->foreign('student_id')->references('id')->on('users');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('student_schedules');
	}
};
