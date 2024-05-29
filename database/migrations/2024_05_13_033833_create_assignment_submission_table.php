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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();

			$table->unsignedBigInteger("assignment_id");
			$table->unsignedBigInteger("student_id");

			$table->string("link");
			$table->string("title");
			$table->longText("feedback")->nullable();
			$table->string("status");

			$table->foreign("student_id")->references("id")->on("users")->onDelete("cascade");
			$table->foreign("assignment_id")->references("id")->on("assignments")->onDelete("cascade");

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
        Schema::dropIfExists('assignment_submission');
    }
};
