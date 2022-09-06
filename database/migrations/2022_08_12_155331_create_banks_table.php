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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->char('bank_name',50);
            $table->text('link_logo');
        });
        DB::unprepared('
            insert into banks(bank_name,link_logo) values
            ("ACB","ACB.png"),
            ("TECHCOMBANK","TECHCOMBANK.png"),
            ("VIETCOMBANK","VIETCOMBANK.png"),
            ("TPBANK","TPBANK.png"),
            ("MBBANK","MBBANK.png");
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
