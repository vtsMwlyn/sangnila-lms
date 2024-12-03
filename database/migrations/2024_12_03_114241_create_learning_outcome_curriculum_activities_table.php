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
        Schema::create('learning_outcome_curriculum_activities', function (Blueprint $table) {
            $table->id();

			$table->unsignedBigInteger("learning_outcome_id");
			$table->foreign("learning_outcome_id", "fk_learning_outcome")->references("id")->on("learning_outcomes")->onDelete("cascade");

			$table->unsignedBigInteger("curriculum_activity_id");
			$table->foreign("curriculum_activity_id", "fk_c_activity")->references("id")->on("curriculum_activities")->onDelete("cascade");

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
        Schema::dropIfExists('learning_outcome_curriculum_activities');
    }
};
