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
        Schema::create('learning_outcome_activities', function (Blueprint $table) {
            $table->id();

			$table->unsignedBigInteger("learning_outcome_id");
			$table->foreign("learning_outcome_id")->references("id")->on("learning_outcomes")->onDelete("cascade");

			$table->unsignedBigInteger("activity_id");
			$table->foreign("activity_id")->references("id")->on("activities")->onDelete("cascade");

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
        Schema::dropIfExists('learning_outcome_activities');
    }
};
