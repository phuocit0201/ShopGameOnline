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
            $table->text('access_token');
            $table->text('partner_key');
            $table->text('partner_id');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
        DB::unprepared('
            insert into thesieure (username,full_name,access_token,partner_key,partner_id,created_at) values
            ("taikhoan","Le Huu Phuoc","abcd1234","abcd1234","12345",current_time());
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
