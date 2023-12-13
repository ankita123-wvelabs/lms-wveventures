<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->string('exmpt')->nullable();
            $table->string('80c')->nullable();
            $table->string('80d')->nullable();
            $table->string('nps')->nullable();
            $table->string('exmpt_proof')->nullable();
            $table->string('80c_proof')->nullable();
            $table->string('80d_proof')->nullable();
            $table->string('nps_proof')->nullable();
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
        Schema::dropIfExists('tds');
    }
}
