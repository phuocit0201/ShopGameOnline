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

    public static function ErrorkPriceCard($price){
        //số đầu tiên của mệnh giá thẻ phải khác 0
        $price.="";
        if($price[0] == 0){
            return true;
        }
        //mệnh giá thẻ phải tròn ví dụ 10000, 200000 chứ không có 15000,250000
        for($i = 1; $i < strlen($price); $i++)
        {
            if($price[$i] != 0){
                return true;
            }
        }
        return false;
    }
}
?>