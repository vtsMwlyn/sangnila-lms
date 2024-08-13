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
        Schema::create('curriculum_materials', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('curriculum_topic_id');
			$table->string('title');
			$table->longText("desc");
			$table->string('link');
			$table->foreign('curriculum_topic_id')->references('id')->on('curriculum_topics')->onDelete("cascade");
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
        Schema::dropIfExists('curriculum_materials');
    }
};
