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
        Schema::create('face_value', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('telco_id')->unsigned();
            $table->integer('price');
            $table->integer('fees');
            $table->integer('penalty');
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->foreign('telco_id')->references('id')->on('telco');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('face_value');
    }
};
