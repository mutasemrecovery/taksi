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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('number')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');

            $table->string('pick_name');
            $table->double('pick_lat');
            $table->double('pick_lng');
   
            $table->string('drop_name');
            $table->double('drop_lat');
            $table->double('drop_lng');

            $table->double('total_price_before_discount');
            $table->double('discount_value')->nullable();
            $table->double('total_price_after_discount');
            $table->double('net_price_for_driver');
            $table->double('commision_of_admin');

            $table->tinyInteger('status')->default(1); // 1 pending // 2 driver_accepted // 3 driver_go_to_user //4 user_with_driver  // 5 delivered // 6 user_cancel_order //7 driver_cancel_order
            $table->text('reason_for_cancel')->nullable();

            $table->tinyInteger('payment_method')->default(1); // 1 cash // 2 visa // 3 wallet
            $table->tinyInteger('status_payment')->default(1); // 1 pending // 2 paid
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
        Schema::dropIfExists('orders');
    }
};
