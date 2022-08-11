<?php
namespace App\Http\Helpers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

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

    public static function requestCardTsr($data)
    {
        $url = 'https://thesieure.com/chargingws/v2';
        $partner_key = '4c5daff8a6f7e0ccf572421246915640';
        $sign = md5($partner_key.'312821445892789'.'10007783347874');
        $respons = Http::acceptJson([
            'application/json'
        ])->post($url,$data);
        return $respons->body();
    }

}
?>