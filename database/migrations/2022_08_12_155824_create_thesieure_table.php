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
        Schema::create('thesieure', function (Blueprint $table) {
            $table->id();
            $table->char('username');
            $table->char('full_name',100);
            $table->text('token_api');
            $table->text('partner_key');
            $table->text('partner_id');
            $table->integer('status_bank')->default(0);
            $table->integer('status_card')->default(0);
            $table->text('note');
            $table->text('link_logo');
            $table->timestamps();
        });
        DB::unprepared('
            insert into thesieure (username,full_name,token_api,partner_key,partner_id,note,link_logo,created_at) values
            ("phuocit0201","Le Huu Phuoc","abcd1234","abcd1234","12345","Vui lòng điền đúng nội dung","logo.png",current_time());
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thesieure');
    }
};
