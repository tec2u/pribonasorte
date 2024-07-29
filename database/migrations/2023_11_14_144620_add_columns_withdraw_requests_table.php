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
        Schema::table('withdraw_requests', function (Blueprint $table) {
            $table->string("account_name")->nullable();
            $table->string("address")->nullable();
            $table->string("account_number")->nullable();
            $table->string("bank_name")->nullable();
            $table->string("iban")->nullable();
            $table->string("swift")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            $table->dropColumn("account_name");
            $table->dropColumn("address");
            $table->dropColumn("account_number");
            $table->dropColumn("bank_name");
            $table->dropColumn("iban");
            $table->dropColumn("swift");
        });
    }
};
