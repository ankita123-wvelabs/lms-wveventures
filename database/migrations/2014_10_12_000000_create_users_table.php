<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('users', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('email')->unique();
			$table->string('image')->nullable();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->string('emp_id')->nullable();
			$table->string('position')->nullable();
			$table->string('reporting_manager')->nullable();
			$table->date('joining_date')->nullable();
			$table->date('dob')->nullable();
			$table->double('salary')->nullable();
			$table->string('face_id')->nullable();
			$table->enum('spacial_case',['0','1'])->default('0');
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('users');
	}
}
