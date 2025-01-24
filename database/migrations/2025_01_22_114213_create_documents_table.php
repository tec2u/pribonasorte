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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // 'user_id', 'bigint(20) unsigned', 'NO', '', NULL, ''
            $table->tinyInteger('backoffice')->default(1); // 'backoffice', 'tinyint(1)', 'NO', '', '1', ''
            $table->string('type', 255); // 'type', 'varchar(191)', 'NO', '', NULL, ''
            $table->text('content'); // 'content', 'text', 'NO', '', NULL, ''
            $table->tinyInteger('approved')->default(0);
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
        Schema::dropIfExists('documents');
    }
};
