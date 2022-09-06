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
            $table->timestamps();
        });

        DB::unprepared('
            insert into momo(phone_number,full_name,token_api,note,created_at) values
            ("so dien thoai","ten","toke api","ghi chu",current_time());
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
