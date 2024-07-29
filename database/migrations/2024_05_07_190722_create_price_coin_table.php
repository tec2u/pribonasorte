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
        Schema::create('price_coin', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('one_in_usd', 12, 4);
            $table->timestamps();
        });

        DB::table('price_coin')->insert([
            ['name' => 'eur', 'one_in_usd' => 1.0814],

        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_coin');
    }
};
