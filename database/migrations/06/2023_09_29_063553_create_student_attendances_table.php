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
		Schema::create('student_attendances', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('student_schedule_id');
			$table->enum('status', ['attended', 'sick', 'skip', 'alpha']);
			$table->string('reason')->nullable();
			$table->date('submit_date');
			$table->time('submit_time');
			$table->enum('validation', ['valid', 'invalid', 'unvalidated']);
			$table->unsignedBigInteger('validated_by_teacher');
			$table->foreign('student_schedule_id')->references('id')->on('student_schedule');
			$table->foreign('validated_by_teacher')->references('id')->on('users');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('student_attendances');
	}
};
