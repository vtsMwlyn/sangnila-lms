<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up() {
		Schema::create('progress', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('student_id');
			$table->unsignedBigInteger('material_id');
			// $table->unsignedBigInteger('teacher_id')->nullable();
			$table->string("already_opened")->default("no");
			$table->unsignedBigInteger('course_id');
			$table->text('note')->nullable();
			$table->enum('status', ['locked', 'unlocked']);
			// ========================================================
			$table->foreign('student_id')->references('id')->on('users')->onDelete("cascade");
			// $table->foreign('teacher_id')->references('id')->on('users')->onDelete("cascade");
			$table->foreign('material_id')->references('id')->on('materials')->onDelete("cascade");
			$table->foreign('course_id')->references('id')->on('courses')->onDelete("cascade");
			$table->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists('progress');
	}
};
