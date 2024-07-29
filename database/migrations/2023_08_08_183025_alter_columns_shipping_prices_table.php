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
            $table->dropColumn('2kg');
            $table->dropColumn('5kg');
            $table->dropColumn('10kg');
            $table->dropColumn('20kg');
            $table->dropColumn('31_5kg');
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
