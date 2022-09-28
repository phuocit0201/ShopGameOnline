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
            $table->char('img',50);
            $table->integer('status')->default(0);
            $table->timestamps();
        });
        DB::unprepared('
            insert into categories(name,slug,img,created_at) values
            ("Ninjaschool",ninjaschool,"http://localhost/ShopGame/asset/categories/ninjaschool.gif",current_time()),
            ("Ninja Quạt Buff",ninja-quat-buff,"http://localhost/ShopGame/asset/categories/ninjaschool-quat-buff.gif",current_time()),
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
