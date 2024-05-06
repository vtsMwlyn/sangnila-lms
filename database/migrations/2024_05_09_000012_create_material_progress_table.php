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
		Schema::create('material_progress', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('student_id');
			$table->unsignedBigInteger('material_id');
			$table->unsignedBigInteger('teacher_id')->nullable();
			$table->unsignedBigInteger('course_id');
			$table->text('note')->nullable();
			$table->enum('status', ['locked', 'unlocked']);
			// ========================================================
			$table->foreign('student_id')->references('id')->on('users');
			$table->foreign('teacher_id')->references('id')->on('users');
			$table->foreign('material_id')->references('id')->on('course_materials');
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
		Schema::dropIfExists('material_progress');
	}
};
