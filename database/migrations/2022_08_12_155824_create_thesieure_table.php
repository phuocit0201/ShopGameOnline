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
        Schema::create('thesieure', function (Blueprint $table) {
            $table->id();
            $table->char('username');
            $table->char('full_name',100);
            $table->text('token');
            $table->text('partner_key');
            $table->text('partner_id');
            $table->text('note');
            $table->integer('status');
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
        Schema::dropIfExists('thesieure');
    }
};
