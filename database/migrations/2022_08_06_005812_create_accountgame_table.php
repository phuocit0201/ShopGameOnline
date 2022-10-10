<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('account_game',function(Blueprint $table){
            $table->id();
            $table->char('class',50);
            $table->char('level',50);
            $table->char('server_game',50);
            $table->integer('family');
            $table->double('import_price');
            $table->double('sale_price');
            $table->text('description');
            $table->text('avatar');
            $table->integer('status')->default(0);
            $table->bigInteger('category_id')->unsigned();
            $table->char('username',100);
            $table->char('password',100);
            $table->foreign('category_id')->references('id')->on('categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_game');
    }
};
