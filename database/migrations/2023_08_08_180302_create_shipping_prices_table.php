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
        Schema::create('shipping_prices', function (Blueprint $table) {
            $table->id();
            $table->string('country')->nullable();
            $table->double('2kg', 5, 2)->nullable();
            $table->double('5kg', 5, 2)->nullable();
            $table->double('10kg', 5, 2)->nullable();
            $table->double('20kg', 5, 2)->nullable();
            $table->double('31_5kg', 5, 2)->nullable();
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
        Schema::dropIfExists('shipping_prices');
    }
};
