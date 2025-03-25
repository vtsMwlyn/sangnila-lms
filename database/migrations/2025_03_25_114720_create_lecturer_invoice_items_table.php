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
        Schema::create('lecturer_invoice_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('lecturer_invoice_id');
            $table->foreign('lecturer_invoice_id')->references('id')->on('lecturer_invoices');

            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses');

            $table->string('description');
            $table->string('task');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('rate');

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
        Schema::dropIfExists('lecturer_invoice_items');
    }
};
