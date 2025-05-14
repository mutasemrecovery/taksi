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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('photo');
            $table->double('start_price')->default(0);
            $table->double('price_per_km')->default(0);
            $table->double('admin_commision')->default(0);
            $table->tinyInteger('type_of_commision')->default(1); // 1 fixed // 2 percent
            $table->tinyInteger('payment_method')->default(1); // 1 cash // 2 visa // 3 wallet
            $table->tinyInteger('activate')->default(1); // 1 active // 2 not active
            $table->integer('capacity')->default(0);
            $table->double('waiting_time')->default(0);
            $table->double('cancellation_fee')->default(0);
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
        Schema::dropIfExists('services');
    }
};
