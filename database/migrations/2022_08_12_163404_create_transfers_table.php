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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->char('type_transfer',50);
            $table->char('tranding_code',100);
            $table->char('message',100);
            $table->double('amount');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });

        /*DB::unprepared('
            create trigger tranfers after insert on transfers for each row
            begin
                declare after_money double;
                declare befor_money double;
                declare check_trans int;
                select count(*) into check_trans from transfers where type_transfer = new.type_transfer and tranding_code = new.tranding_code;
                select money into after_money from users where id = new.user_id;
                set befor_money = after_money + new.amount;
                if(check_trans = 1)then
                    update users set money = befor_money where id = new.user_id;
                    insert into transaction_history (user_id,action_id,action_flag,after_money,transaction_money,befor_money,note,created_at,updated_at)
                    values(new.user_id,new.id,4,after_money,concat("+",new.amount),befor_money,concat("Nap tien tu ",new.type_transfer),current_time(),current_time());
                else
                    SIGNAL sqlstate "45001" set message_text = "transfers exist";
                end if;
            end
        ');*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
};
