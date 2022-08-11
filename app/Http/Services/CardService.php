<?php
namespace App\Http\Services;

use App\Models\Card;
use Exception;
use Illuminate\Support\Facades\DB;

class CardService{
    public static function getHistoryByUser($user_id)
    {
        return DB::table('cards')->join('face_value','cards.face_value_id','=','face_value.id')
        ->join('telco','telco.id','=','face_value.telco_id')
        ->select('cards.*','face_value.price','telco.telco_name')
        ->where('cards.user_id',$user_id)
        ->get();
    }
    //get mệnh giá thẻ cào vào loại thẻ cào
    public static function getFaceValueCard($id)
    {
        return DB::table('face_value')->join('telco','face_value.telco_id','=','telco.id')
        ->select('face_value.price','telco.telco_name')
        ->where('face_value.id',$id)->first();
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

    public static function update($requestId,$data)
    {
        try{
            DB::table('cards')->where('request_id',$requestId)->update($data);
            return true;
        }catch(Exception $e){
            return false;
        }
    }
}
?>