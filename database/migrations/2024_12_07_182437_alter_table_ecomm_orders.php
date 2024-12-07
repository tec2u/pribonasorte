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
        Schema::table('ecomm_orders', function (Blueprint $table) {
            $table->string('id_payment_order', 255)->change();
            $table->string('id_payment_recurring', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecomm_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('id_payment_order')->change();
        });
    }
};
