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
        Schema::create('categories',function(Blueprint $table){
            $table->id();
            $table->char('name',50);
            $table->char('slug',100);
            $table->text('img');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
        DB::unprepared('
            insert into categories(name,slug,img,created_at) values
            ("Ninjaschool","ninjaschool","ninjaschool.gif",current_time()),
            ("Ninja Quạt Buff","ninja-quat-buff","ninjaschool-quat-buff.gif",current_time());
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
