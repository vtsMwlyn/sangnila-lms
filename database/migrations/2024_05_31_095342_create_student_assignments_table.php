<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_assignments', function (Blueprint $table) {
            $table->id();

			$table->unsignedBigInteger("student_id");
			$table->unsignedBigInteger("assignment_id");

			$table->foreign("student_id")->references("id")->on("users")->onDelete("cascade");
			$table->foreign("assignment_id")->references("id")->on("assignments")->onDelete("cascade");

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_assignments');
    }
};
