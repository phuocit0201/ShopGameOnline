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
        DB::unprepared('
            create trigger UpdateMoneyUser after insert on orders for each row
            begin
                declare price_account double;
                declare after_money double;
                declare befor_money double;
                declare check_buy int;
                select sale_price into price_account  from account_game where id = new.account_id;
                select money into after_money from users where id = new.user_id;
                select status into check_buy from account_game where id = new.account_id;
                if(after_money < price_account || check_buy != 0) then
                    SIGNAL sqlstate "45001" set message_text = "error";
                else
                    set befor_money = after_money - price_account;
                    update users set money = befor_money where id = new.user_id;
                    update account_game set status = 3 where id = new.account_id;
                    insert into transaction_history (user_id,action_id,action_flag,after_money,transaction_money,befor_money,created_at,updated_at)
                    values(new.user_id,new.id,1,after_money,concat("-",price_account),befor_money,current_time(),current_time());
                end if;
            end
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
