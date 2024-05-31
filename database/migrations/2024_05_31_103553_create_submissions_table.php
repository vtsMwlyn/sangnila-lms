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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

			$table->unsignedBigInteger("student_assignment_id");
			$table->string("link");
			$table->string("title");
			$table->longText("feedback")->nullable();
			$table->string("status");

			$table->foreign("student_assignment_id")->references("id")->on("student_assignments");

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
        Schema::dropIfExists('submissions');
    }
};
