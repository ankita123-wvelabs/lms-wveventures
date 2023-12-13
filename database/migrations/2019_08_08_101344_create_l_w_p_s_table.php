<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLWPSTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('l_w_p_s', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedInteger('user_id');
			$table->year('year');
			$table->tinyInteger('month');
			$table->tinyInteger('count');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('l_w_p_s');
	}
}
