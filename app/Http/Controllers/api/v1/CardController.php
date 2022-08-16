<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\CardService;
use App\Http\Services\FaceValueService;
use App\Http\Services\TheSieuReService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    private $user;
    private $user_id;
    private $partner_id = "0354110661";
    private $partner_key = "4c5daff8a6f7e0ccf572421246915640";
    private $url = "https://thesieure.com/chargingws/v2";
    public function __construct()
    {
        $this->user = response()->json(Auth::guard()->user());
        $this->user_id = $this->user->getData()->id;
    }

    public function getHistoryByUser()
    {
        $history = CardService::getHistoryByUser($this->user_id);
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$history,200);
    }

    public function requestCardTsr(Request $request)
    {
        //kiểm tra xem hệ thống nạp thẻ có bảo trì không
        $theSieuRe = TheSieuReService::getTSR();
        if($theSieuRe->status !== 0)
        {
            return FunResource::responseNoData(false,Mess::$SYSTEM_MAINTENANCE,401);
        }
        //lấy ra thông tin mệnh giá và nhà mạng mà người dùng gửi
        $face_value = CardService::getFaceValueCard($request->id);
        if(!$face_value){
            return FunResource::responseNoData(false,Mess::$CARD_NOT_EXIST,400);
        }
        //nếu là nhà mạng VIETTEL VINA MOBI thì serial và mã thẻ phải là số
        if($face_value->telco_name === 'VIETTEL' || $face_value->telco_name === 'VINAPHONE' || $face_value->telco_name === 'MOBIFONE'){
            $validator = Validator::make($request->all(),[
                'code' => 'required|numeric',
                'serial' => 'required|numeric'
            ]);
            if($validator->fails()){
                return FunResource::responseData(false,Mess::$INVALID_INFO,$validator->errors()->toArray(),404);
            }
        }
        //request id gửi lên thẻ siêu rẻ = số lượng card hiện tại cộng thêm 1111111111 để khỏi bị trùng
        $request_id =  CardService::countCards() + 11111;

        
        $sign = md5($this->partner_key.$request->code.$request->serial);
        $card = [
            'telco' => $face_value->telco_name,
            'serial' => $request->serial,
            'code' => $request->code,
            'amount' => $face_value->price,
            'request_id' => $request_id,
            'partner_id' => $this->partner_id,
            'sign' => $sign,
            'command'=>'charging'
        ];

        //gửi card lên thesieure.com
        $respons = FunResource::requestDataPost($this->url,$card);
        //gửi thẻ thành công lên thẻ siêu rẻ thì thêm thẻ này vào database
        $result = json_decode($respons,true);
        if($result['status'] < 100)
        {
            $insertCard = [
                'serial' => $request->serial,
                'code' => $request->code,
                'user_id' =>$this->user_id,
                'face_value_id' => $request->id,
                'request_id' => $request_id,
                'status' => $result['status']
            ];
            CardService::create($insertCard);
            return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
        }
        return FunResource::responseNoData(false,$result["message"],400);
    }
}
