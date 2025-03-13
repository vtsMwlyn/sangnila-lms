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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('teacher_id');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');

            $table->enum('performance_score', ['excellent', 'very good', 'good', 'rooms for growth']);
            $table->longText('performance_description');
            $table->enum('technical_skill_score', ['excellent', 'very good', 'good', 'rooms for growth']);
            $table->longText('technical_skill_description');
            $table->enum('aesthetical_skill_score', ['excellent', 'very good', 'good', 'rooms for growth']);
            $table->longText('aesthetical_skill_description');
            $table->enum('overall_score', ['excellent', 'very good', 'good', 'rooms for growth']);
            $table->longText('overall_description');

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
        Schema::dropIfExists('assessments');
    }
};
