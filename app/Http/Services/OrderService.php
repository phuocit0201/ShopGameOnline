<?php
namespace App\Http\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService{
    //tạo đơn hàng
    public static function create($order)
    {
        try{
            return Order::create($order);
        }catch(Exception $e){
            return null;
        }
    }

    //xem lại chi tiết đơn hàng mà người dùng đã mua
    public static function orderDetail($account_id,$user_id)
    {
        return DB::table('account_game')->join('orders','account_game.id','=','orders.account_id')
        ->where('account_game.id',$account_id)
        ->where('orders.user_id',$user_id)
        ->select('account_game.*')
        ->first();
    }
}
?>