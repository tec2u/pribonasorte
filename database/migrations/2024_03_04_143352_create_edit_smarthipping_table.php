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
        Schema::create('edit_smarthipping', function (Blueprint $table) {
            $table->id();
            $table->integer('number_order')->nullable();
            $table->integer('id_user')->nullable();
            $table->integer('id_product')->nullable();
            $table->integer('amount')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->decimal('total_vat', 10, 2)->nullable();
            $table->decimal('total_shipping', 10, 2)->nullable();
            $table->decimal('qv', 10, 2)->nullable();
            $table->decimal('cv', 10, 2)->nullable();
            $table->integer('client_backoffice')->nullable();
            $table->decimal('vat_product_percentage', 10, 2)->nullable();
            $table->decimal('vat_shipping_percentage', 10, 2)->nullable();
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
        Schema::dropIfExists('edit_smarthipping');
    }
};
