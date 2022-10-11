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
        Schema::create('images_details', function (Blueprint $table) {
            $table->id();
            $table->text('link_img');
            $table->bigInteger('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('account_game');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images_details');
    }
};
