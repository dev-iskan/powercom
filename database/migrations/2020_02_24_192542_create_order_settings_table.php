<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('status_created')->nullable();
            $table->foreign('status_created')->references('id')->on('order_statuses');

            $table->unsignedBigInteger('status_in_progress')->nullable();
            $table->foreign('status_in_progress')->references('id')->on('order_statuses');

            $table->unsignedBigInteger('status_completed')->nullable();
            $table->foreign('status_completed')->references('id')->on('order_statuses');

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
        Schema::dropIfExists('order_settings');
    }
}
