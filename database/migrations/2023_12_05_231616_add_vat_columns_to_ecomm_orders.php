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
        Schema::table('ecomm_orders', function (Blueprint $table) {
            $table->decimal('vat_product_percentage', 5, 2)->nullable();
            $table->decimal('vat_shipping_percentage', 5, 2)->nullable();
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
            $table->dropColumn('vat_product_percentage');
            $table->dropColumn('vat_shipping_percentage');
        });
    }
};
