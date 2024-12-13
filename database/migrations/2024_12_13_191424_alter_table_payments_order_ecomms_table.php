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
        Schema::table('payments_order_ecomms', function (Blueprint $table) {
            $table->string('id_payment_gateway', 255)->change();
            $table->string('id_invoice_trans', 255)->change();
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
            $table->unsignedBigInteger('id_payment_order')->change();
        });
    }
};
