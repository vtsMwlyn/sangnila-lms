<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

			$table->unsignedBigInteger('teacher_id');
			$table->unsignedBigInteger('course_id');
			// $table->unsignedBigInteger('schedule_id');
			$table->string("title");
			$table->longText("desc");
			$table->string("link");
			$table->date("deadline_date");
			$table->time("deadline_time");

			// $table->foreign('schedule_id')->references('id')->on('course_schedules');
			$table->foreign('teacher_id')->references('id')->on('users')->onDelete("cascade");
			$table->foreign('course_id')->references('id')->on('courses')->onDelete("cascade");

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assignment');
    }
};
