<?php
namespace App\Http\Services;

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
}
?>