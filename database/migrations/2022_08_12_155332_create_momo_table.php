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
            $table->text('access_token');
            $table->text('note')->nullable();
            $table->text('link_logo');
            $table->integer('status')->default(0);
            $table->timestamps();
        });

        DB::unprepared('
            insert into momo(phone_number,full_name,access_token,note,link_logo,created_at) values
            ("so dien thoai","ten","toke api","ghi chu","link logo",current_time());
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
