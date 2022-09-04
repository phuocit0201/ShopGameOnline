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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            //$table->bigInteger('face_value_id')->unsigned();
            $table->char('telco',20);
            $table->integer('declare_value');
            $table->integer('fees');
            $table->integer('penalty');
            $table->char('serial',50);
            $table->char('code',50);
            $table->integer('value')->default(0);
            $table->integer('amount')->default(0);
            $table->integer('status')->default(99);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            //$table->foreign('face_value_id')->references('id')->on('face_value');
        });
        //tạo trigger cộng tiền cho người dùng
        /*DB::unprepared('
            create trigger UpMoneyCards before update  on cards for each row
            begin
                declare _fees double; declare _amount double; declare _value double;
                declare after_money double; declare befor_money double; declare _penalty double;
                if(new.status = 1 && old.status != 1 && old.status != 2 || new.status = 2 && old.status != 1 && old.status != 2)then
                    -- lấy ra phí nạp thẻ
                    select fees into _fees from face_value fv join cards c on fv.id = c.face_value_id where c.id = new.id;
                    -- lấy ra số tiền hiện có của user
                    select money into after_money from users where id = new.user_id;
                    -- gán giá trị thực của thẻ
                    set _value = new.value;
                    -- trạng thái thẻ đúng và đúng mệnh giá nên không bị phạt
                    if(new.status = 1)then
                        set _amount = (_value * (100 - _fees)) / 100;
                    else
                    -- trường hợp thẻ đúng nhưng khai báo sai mệnh giá nên bị phạt
                        select penalty into _penalty from face_value fv join cards c on fv.id = c.face_value_id where c.id = new.id;
                        set _amount = (_value * (100 - _penalty)) / 100;
                    end if;
                    -- gán số tiền sau khi nạp thẻ thành công
                    set befor_money = after_money + _amount;
                    -- cập nhật lại tiền của user
                    update users set money = befor_money where id = new.user_id;
                    -- thêm vào biến động số dư
                    insert into transaction_history (user_id,action_id,action_flag,after_money,transaction_money,befor_money,note,created_at,updated_at)
                    values(new.user_id,new.id,3,after_money,concat("+",_amount),befor_money,"Nap the thanh cong",current_time(),current_time());
                    -- cập nhật lại tiền thực mà user nhận được
                    set new.amount = _amount;
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
        Schema::dropIfExists('cards');
    }
};
