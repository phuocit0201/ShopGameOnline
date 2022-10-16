<?php
namespace App\Http\Helpers;

use App\Http\Services\SettingService;
use App\Http\Services\TheSieuReService;
use App\Http\Services\TransHistoryService;
use App\Http\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FunResource{
    public static function responseNoData($status,$mess,$code){
        return response()->json(["status"=>$status,"mess"=>$mess],$code);
    }

    public static function responseData($status,$mess,$data,$code){
        return response()->json(["status"=>$status,"data"=>$data,"mess"=>$mess],$code);
    }

    public static function respondWithToken($token)
    {
        return response()->json([
            "status"=>true,
            'mess'=>'successfully',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }

    public static function checkIsAdmin()
    {
        try{
            $user =  response()->json(Auth::guard()->user());
            if($user && $user->getData()->role === 1){
                if($user->getData()->banned === 1)
                {
                    Auth::logout();
                    return Redirect(route('tokenNotExist'));
                }
                return true;
            }
            return false;
        }catch(Exception $e){
            return false;
        }
    }

    public static function requestDataPost($url,$data)
    {
        $respons = Http::acceptJson([
            'application/json'
        ])->post($url,$data);
        return $respons->body();
    }

    public static function requestGet($url)
    {
        $respons = Http::get($url);
        return $respons->body();
    }  

    public static function parseComment($command,$comment){
        $re = '/'.$command.'\d+/im';
        preg_match_all($re, $comment, $matches, PREG_SET_ORDER, 0);
        if (count($matches) == 0 )
            return null;
        // Print the entire match result
        $orderCode = $matches[0][0];
        $prefixLength = strlen($command);
        $orderId = intval(substr($orderCode, $prefixLength ));
        return $orderId ;
    }

    public static function upMoneyForUser($data){
        //kiểm tra xem user cần cộng tiền có tồn tại trên hệ thống không
        $user = UserService::getUserById($data['user_id']);
        if(!$user){
            return false;
        }
        $data['after_money'] = $user->money;
        $data['befor_money'] = $user->money + $data['transaction_money'];
        $transHistory = TransHistoryService::create($data);
        if(!$transHistory){
            return false;
        }
        $user->update(['money'=>$data['befor_money']]);
        return true;
    }

    //kiểm tra xem thẻ cào này tồn tại không
    public static function checkCard($telco,$value){
        $url = "https://thesieure.com/chargingws/v2/getfee?partner_id=";
        $partner_id = TheSieuReService::getTSR()->partner_id;
        $listCard = json_decode(FunResource::requestGet($url.$partner_id),true);
        foreach($listCard as $key => $values){
            if($values['telco'] == $telco && $values['value'] == $value){
                return $values;
            }
        }
    }

    public static function getFee(){
        $partner_id = TheSieuReService::getTSR()->partner_id;
        $url = "https://thesieure.com/chargingws/v2/getfee?partner_id=$partner_id";
        $listCard = json_decode(FunResource::requestGet($url),true);
        return $listCard;
    }

    public static function site($keyName){
        return SettingService::getValueSettings($keyName)->value;
    }

    public static function urlApiAtm($atm){
        switch($atm){
            case 'ACB':
                return 'historyapiacbv3';
            case 'TECHCOMBANK':
                return 'historyapitcbv3';
            case 'VIETCOMBANK':
                return 'historyapivcbv3';
            case 'MBBANK':
                return 'historyapimbv3';
        }
    }
}
?>