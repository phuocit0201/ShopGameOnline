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
        Schema::create('type_atm', function (Blueprint $table) {
            $table->id();
            $table->char('atm_name',50);
        });
        DB::unprepared('
            insert into type_atm(atm_name) values
            ("ACB"),("TECHCOMBANK"),("VIETCOMBANK"),("TPBANK"),("MBBANK");
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_atm');
    }
};
