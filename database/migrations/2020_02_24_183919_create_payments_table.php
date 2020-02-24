<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->unsignedBigInteger('amount');
            $table->string('payment_method')->nullable();

            $table->boolean('paid')->default(false);
            $table->timestamp('paid_time')->nullable();

            $table->boolean('cancelled')->default(false);
            $table->timestamp('cancelled_time')->nullable();

            $table->string('payme_receipt_id')->nullable();
            $table->timestamp('payme_time')->nullable();
            $table->integer('payme_state')->nullable();
            $table->timestamp('payme_perform_time')->nullable();
            $table->integer('payme_cancel_reason')->nullable();
            $table->timestamp('payme_cancel_time')->nullable();

            $table->string('click_trans_id')->nullable();
            $table->integer('click_status')->nullable();
            $table->timestamp('click_time')->nullable();
            $table->timestamp('click_perform_time')->nullable();
            $table->timestamp('click_cancel_time')->nullable();

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
        Schema::dropIfExists('payments');
    }
}
