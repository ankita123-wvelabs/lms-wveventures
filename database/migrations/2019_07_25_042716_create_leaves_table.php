<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('leaves', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedInteger('user_id');
			$table->string('date');
			$table->enum('type', ['Full Day', 'Half Day'])->default('Full Day');
			$table->text('reason');
			$table->enum('type_description', ['First Half', 'Second Half'])->nullable();
			$table->enum('status', ['Pending', 'Approved', 'Rejected', 'Cancel'])->default('Pending');
			$table->year('year');
			$table->tinyInteger('half_day_count')->default(0);
			$table->boolean('is_lwp')->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('leaves');
	}
}
