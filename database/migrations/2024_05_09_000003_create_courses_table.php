<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up() {
		Schema::create('courses', function (Blueprint $table) {
			$table->id();

			$table->string('course_name');
			$table->text('course_description');
			$table->enum('level', ['basic', 'intermediate', 'advanced']);
			$table->unsignedInteger('format');

			$table->enum('status', ['active', 'disabled']);

			$table->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists('courses');
	}
};
