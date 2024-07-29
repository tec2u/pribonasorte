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
        Schema::table('smartshipping_payments_recurring', function (Blueprint $table) {
            $table->string('new_order')->nullable();
        });
    }

    public function down()
    {
        Schema::table('smartshipping_payments_recurring', function (Blueprint $table) {
            $table->dropColumn('new_order');
        });
    }
};
