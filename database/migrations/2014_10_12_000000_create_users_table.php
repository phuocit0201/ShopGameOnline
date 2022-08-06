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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->char('username',50)->unique();
            $table->string('password');
            $table->char('name',50);
            $table->char('email',100)->unique();
            $table->integer('role')->default(0);
            $table->double('money')->default(0);
            $table->integer('banned')->default(0);
            $table->string('reason_banned')->nullable();
            $table->char('ip',50);
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
        Schema::dropIfExists('users');
    }
};
