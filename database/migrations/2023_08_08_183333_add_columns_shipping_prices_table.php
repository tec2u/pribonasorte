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
        Schema::table('shipping_prices', function (Blueprint $table) {
            $table->double('kg2', 5, 2);
            $table->double('kg5', 5, 2);
            $table->double('kg10', 5, 2);
            $table->double('kg20', 5, 2);
            $table->double('kg31_5', 5, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_prices', function (Blueprint $table) {
            //
        });
    }
};
