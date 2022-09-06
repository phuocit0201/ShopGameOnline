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
        Schema::create('atm', function (Blueprint $table) {
            $table->id();
            $table->char('account_number',50);
            $table->char('full_name',100);
            $table->char('password');
            $table->text('token_api');
            $table->text('note');
            $table->integer('status')->default(0);
            $table->bigInteger('bank_id')->unsigned();
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->timestamps();
        });
        DB::unprepared('
            insert into atm(account_number,full_name,password,token_api,note,bank_id)value
            ("0845151117","LE HUU PHUOC","huuphuoc","tokenapi","Chi Nhanh Da Nang",1);
        ');
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
