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
        Schema::create('rotation_history', function (Blueprint $table) {
            $table->id();
            $table->integer('coins');
            $table->bigInteger('rotation_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('status')->default(0);
            $table->foreign('rotation_id')->references('id')->on('rotation_luck');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('rotation_history');
    }
};
