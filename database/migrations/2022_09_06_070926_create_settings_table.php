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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->char('key_name',50);
            $table->text('value');
            $table->timestamps();
        });
        DB::unprepared('
                insert into settings(key_name,value,created_at) values
                ("name_website","ShopGameNso",current_time()),
                ("decs_website","mo ta website",current_time()),
                ("logo_website","logo.jpg",current_time()),
                ("favicon","icon",current_time()),
                ("color","#333",current_time()),
                ("notification","thong bao",current_time()),
                ("email","huuphuoc@gmail.com",current_time()),
                ("facebook","link_facebook",current_time()),
                ("phone","0845151117",current_time()),
                ("recharge_min","10000",current_time()),
                ("command_bank","NAPTIEN",current_time()),
                ("maintenance","ON",current_time()),
                ("fanpage","script",current_time());
            ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
