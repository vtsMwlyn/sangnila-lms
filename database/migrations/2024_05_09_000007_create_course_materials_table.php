<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up() {
		Schema::create('course_materials', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('course_topic_id');
			$table->string('title');
			$table->string('link');
			$table->foreign('course_topic_id')->references('id')->on('course_topics')->onDelete("cascade");
			$table->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists('course_materials');
	}
};
