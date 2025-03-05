<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up() {
		Schema::create('users', function (Blueprint $table) {
			$table->id();

			$table->unsignedBigInteger('role_id');
			$table->foreign('role_id')->references('id')->on('roles')->onDelete("cascade");

			$table->string('full_name');
			$table->string('email')->unique();
			$table->string('password');
			$table->timestamp('email_verified_at')->nullable();

			$table->string("status");
			$table->timestamp("last_announcement")->nullable();
			$table->timestamp("last_login")->nullable();
			$table->string("disable_reason")->nullable();
			$table->unsignedInteger("can_swap_role")->default(0);

			$table->rememberToken();

			$table->timestamps();
		});
	}

	public function down() {
		Schema::dropIfExists('users');
	}
};
