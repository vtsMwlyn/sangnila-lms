<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up() {
		Schema::create('materials', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('topic_id');
			$table->string('title');
			$table->longText("desc");
			$table->longText('link')->nullable();
			$table->foreign('topic_id')->references('id')->on('topics')->onDelete("cascade");
			$table->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists('materials');
	}
};
