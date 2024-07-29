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
        Schema::create('chosen_pickup', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->string('accessPointType');
            $table->string('code');
            $table->string('dhlPsId');
            $table->string('depot');
            $table->string('depotName');
            $table->string('name');
            $table->string('street');
            $table->string('city');
            $table->string('zipCode');
            $table->string('country');
            $table->string('parcelshopName');
            $table->string('number_order');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chosen_pickup');
    }
};