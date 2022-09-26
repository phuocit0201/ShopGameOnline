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
        Schema::create('momo', function (Blueprint $table) {
            $table->id();
            $table->char('phone_number',20);
            $table->char('full_name',100);
            $table->text('token_api');
            $table->text('note')->nullable();
            $table->integer('status')->default(0);
            $table->text('link_logo');
            $table->timestamps();
        });

        DB::unprepared('
            insert into momo(phone_number,full_name,token_api,note,link_logo,created_at) values
            ("0845151117","LE HUU PHUOC","tokeapi","Vui lòng điền nhập đúng nội dung","logo.png",current_time());
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('momo');
    }
};
