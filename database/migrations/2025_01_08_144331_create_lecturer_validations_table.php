<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecturer_validations', function (Blueprint $table) {
            $table->id();

			$table->unsignedBigInteger('teacher_id');
			$table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');

			$table->unsignedBigInteger('course_id');
			$table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

			$table->unsignedBigInteger('validator_id');
			$table->foreign('validator')->references('id')->on('users')->onDelete('cascade');

			$table->string('evidence');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lecturer_validations');
    }
};
