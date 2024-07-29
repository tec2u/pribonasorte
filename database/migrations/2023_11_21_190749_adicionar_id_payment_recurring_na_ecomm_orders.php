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
            $table->unsignedBigInteger('id_payment_recurring')->nullable(); // Substitua 'outro_campo_existente' pelo nome do campo depois do qual você quer adicionar este campo
        });
    }

    public function down()
    {
        Schema::table('ecomm_orders', function (Blueprint $table) {
            $table->dropColumn('id_payment_recurring');
        });
    }
};
