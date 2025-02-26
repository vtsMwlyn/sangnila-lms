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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id');
			$table->unsignedBigInteger('course_id');

            $table->string('type');
            $table->string('path');
            $table->string('note')->nullable();
            $table->date('portfolio_date')->nullable();

            $table->foreign('student_id')->references('id')->on('users')->onDelete("cascade");
			$table->foreign('course_id')->references('id')->on('courses')->onDelete("cascade");

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
        Schema::dropIfExists('portfolios');
    }
};
