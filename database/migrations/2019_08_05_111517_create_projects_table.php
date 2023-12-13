<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('projects', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('title');
			$table->string('logo')->nullable();
			$table->string('platform')->nullable();
			$table->date('deadline');
			$table->enum('status', ['Design', 'Integration', 'Development', 'Completed', 'On Hold', 'QA'])->default('Integration');
			$table->text('user_ids')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('projects');
	}
}
