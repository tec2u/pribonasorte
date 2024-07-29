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
        Schema::create('ecomm_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('number_order');
            $table->integer('id_user');
            $table->integer('id_product');
            $table->integer('amount');
            $table->decimal('total', 5, 2);
            $table->string('status_order');
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
        Schema::dropIfExists('ecomm_orders');
    }
};
