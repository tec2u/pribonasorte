<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('payments_order_ecomms', function (Blueprint $table) {
            $table->tinyInteger('order_corporate')->nullable()->default(0);
        });
    }

    public function down()
    {
        Schema::table('payments_order_ecomms', function (Blueprint $table) {
            $table->dropColumn('order_corporate');
        });
    }
};
