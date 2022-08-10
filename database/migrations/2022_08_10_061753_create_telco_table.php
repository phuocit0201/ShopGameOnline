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
        Schema::create('telco', function (Blueprint $table) {
            $table->id();
            $table->char('telco_name');
            $table->integer('status');
            $table->timestamps();
        });
        DB::unprepared('
            insert into telco(telco_name,status,created_at) values
            ("VIETTEL",0,current_time()),
            ("VINAPHONE",0,current_time()),
            ("MOBIFONE",0,current_time()),
            ("GATE",0,current_time()),
            ("ZING",0,current_time());
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telco');
    }
};
