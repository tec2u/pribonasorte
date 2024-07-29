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
        Schema::table('ecomm_registers', function (Blueprint $table) {
            $table->renameColumn('cpf', 'identity_card');
            $table->renameColumn('cnpj', 'id_corporate');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ecomm_registers', function (Blueprint $table) {
            $table->renameColumn('identity_card', 'cpf');
            $table->renameColumn('id_corporate', 'cnpj');
        });
    }
};