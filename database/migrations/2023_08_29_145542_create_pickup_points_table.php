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
        Schema::create('pickup_points', function (Blueprint $table) {
            $table->id();
            $table->string('country')->nullable();
            $table->double('kg2', 5, 2)->nullable();
            $table->double('kg5', 5, 2)->nullable();
            $table->double('kg10', 5, 2)->nullable();
            $table->double('kg20', 5, 2)->nullable();
            $table->double('kg31_5', 5, 2)->nullable();
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
        Schema::dropIfExists('pickup_points');
    }
};
