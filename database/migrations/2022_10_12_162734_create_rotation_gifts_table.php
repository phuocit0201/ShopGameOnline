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
        Schema::create('rotation_gifts', function (Blueprint $table) {
            $table->id();
            $table->integer('ratio');
            $table->integer('coins');
            $table->bigInteger('rotation_id')->unsigned();
            $table->foreign('rotation_id')->references('id')->on('rotation_luck');
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
        Schema::dropIfExists('rotation_gifts');
    }
};
