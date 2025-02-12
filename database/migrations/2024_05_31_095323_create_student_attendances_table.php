<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();

			$table->unsignedBigInteger("student_id");
			$table->unsignedBigInteger("attendance_id");

			$table->unsignedInteger('nth_session');
			$table->time('start_time');
			$table->time('end_time');

			$table->unsignedInteger("is_attend");
			$table->longText('attendance_detail');
			$table->longText("activity_progress")->nullable();
			$table->string("learning_status")->nullable();

			$table->foreign("student_id")->references("id")->on("users")->onDelete("cascade");
			$table->foreign("attendance_id")->references("id")->on("attendances")->onDelete("cascade");

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_attendances');
    }
};
