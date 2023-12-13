<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('device_id');
            $table->string('device_name');
            $table->string('device_app_version_code')->nullable();
            $table->string('device_app_version_name')->nullable();
            $table->string('device_wifi_mac_address')->nullable();
            $table->string('device_bluetooth_mac_address')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('location')->nullable();
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
        Schema::dropIfExists('auth_devices');
    }
}
