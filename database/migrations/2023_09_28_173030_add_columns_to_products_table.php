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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('backoffice_price', 10, 2)->nullable()->after('premium_price');
            $table->decimal('qv', 10, 2)->nullable()->after('backoffice_price');
            $table->decimal('cv', 10, 2)->nullable()->after('qv');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('backoffice_price');
            $table->dropColumn('qv');
            $table->dropColumn('cv');
        });
    }
};