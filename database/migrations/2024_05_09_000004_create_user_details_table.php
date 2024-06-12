<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up() {
		Schema::create('user_details', function (Blueprint $table) {
			$table->id();

			$table->unsignedBigInteger('user_id');

			$table->string("gender");
			$table->string("phone_number")->nullable();
			$table->string("city_of_birth")->nullable();
			$table->date("date_of_birth")->nullable();

			// For students only (if not a student then can be null
			$table->string('name_parent')->nullable();
			$table->string("phone_parent")->nullable();
			$table->string("student_level")->nullable();
			$table->string('school_name')->nullable();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

			$table->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists('user_details');
	}
};
