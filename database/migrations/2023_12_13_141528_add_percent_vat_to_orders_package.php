<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('orders_package', function (Blueprint $table) {
            $table->float('percent_vat')->nullable();
        });
    }

    public function down()
    {
        Schema::table('orders_package', function (Blueprint $table) {
            $table->dropColumn('percent_vat');
        });
    }
};
