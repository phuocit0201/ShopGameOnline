<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::unprepared('
            insert into face_value(telco_id,price,fees,penalty) values
            (1,10000,18,50),(1,20000,18,50),(1,30000,18,50),(1,50000,15,50),(1,100000,15,50),(1,200000,16,50),(1,300000,16,50),(1,500000,19,50),(1,1000000,20,50),
            (2,10000,19,50),(2,20000,19,50),(2,30000,19,50),(2,50000,18,50),(2,100000,18,50),(2,200000,18,50),(2,300000,18,50),(2,500000,19,50),
            (3,10000,24,50),(3,20000,24,50),(3,30000,24,50),(3,50000,24,50),(3,100000,24,50),(3,200000,24,50),(3,300000,24,50),(3,500000,24,50),
            (4,10000,30,50),(4,20000,30,50),(4,30000,30,50),(4,50000,30,50),(4,100000,30,50),(4,200000,30,50),(4,300000,30,50),(4,500000,30,50),(4,1000000,30,50),(4,2000000,30,50),(4,5000000,30,50),(4,10000000,30,50),
            (5,10000,18,50),(5,20000,18,50),(5,30000,18,50),(5,50000,18,50),(5,100000,18,50),(5,200000,18,50),(5,300000,18,50),(5,500000,18,50);
        ');
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
