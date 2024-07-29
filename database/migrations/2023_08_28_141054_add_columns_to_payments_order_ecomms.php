<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments_order_ecomms', function (Blueprint $table) {
            $table->string('payment_method')->nullable();
            $table->unsignedBigInteger('number_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments_order_ecomms', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('number_order');
        });
    }
};