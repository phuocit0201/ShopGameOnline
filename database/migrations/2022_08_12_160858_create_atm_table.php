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
        Schema::create('atm', function (Blueprint $table) {
            $table->id();
            $table->char('username');
            $table->char('password');
            $table->text('token');
            $table->text('note');
            $table->integer('status');
            $table->bigInteger('type_atm')->unsigned();
            $table->foreign('type_atm')->references('id')->on('type_atm');
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
        Schema::dropIfExists('atm');
    }
};
