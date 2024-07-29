<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sended_alert_smart', function (Blueprint $table) {
            $table->id();
            $table->string('number_order');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sended_alert_smart');
    }
};
