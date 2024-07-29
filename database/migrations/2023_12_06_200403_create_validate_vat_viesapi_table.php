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
    public function up() {
        Schema::create('validate_vat_viesapi', function (Blueprint $table) {
            $table->id();
            $table->text('uid');
            $table->text('country_code');
            $table->text('vat_number');
            $table->text('valid');
            $table->text('trader_name');
            $table->text('trader_company_type');
            $table->text('trader_address');
            $table->text('return_id');
            $table->text('date');
            $table->text('source');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('validate_vat_viesapi');
    }
};
