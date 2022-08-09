<?php
namespace App\Http\Services;

use App\Models\Order;
use Exception;

class OrderService{
    public static function create($order)
    {
        try{
            return Order::create($order);
        }catch(Exception $e){
            return null;
        }
    }
}
?>