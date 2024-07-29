<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('address_billing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('country');
            $table->string('city');
            $table->string('zip');
            $table->string('state');
            $table->string('address');
            $table->string('number_residence');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('address_billing');
    }
};
