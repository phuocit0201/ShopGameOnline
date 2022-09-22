<?php
namespace App\Http\Services;

use App\Models\Card;
use Exception;
use Illuminate\Support\Facades\DB;

class CardService{
    public static function getHistoryByUser($user_id, $perPage)
    {
        return DB::table('cards')
        ->where('user_id',$user_id)
        ->orderByDesc('id')
        ->paginate($perPage);
    }

    public static function create($card)
    {
        try{
            return Card::create($card);
        }catch(Exception $e){
            return null;
        }
    }

    //đếm xem có bao nhiêu cá thẻ cào trong database
    public static function countCards()
    {
        return DB::table('cards')->count();
    }

    public static function update($telco,$serial,$code,$data)
    {
        //try{
            DB::table('cards')
            ->where('serial',$serial)
            ->where('code',$code)
            ->where('telco',$telco)
            ->update($data);
            return true;
        // }catch(Exception $e){
        //     return false;
        // }
    }

    public static function getCardByRequestId($code,$serial,$telco)
    {
        try{
            return DB::table('cards')
            ->where('code',$code)
            ->where('serial',$serial)
            ->where('telco',$telco)
            ->first();
        }catch(Exception $e){
            return null;
        }
    }
}
?>