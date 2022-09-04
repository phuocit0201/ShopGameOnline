<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\CardService;
use App\Http\Services\TheSieuReService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    private $user;
    private $user_id;
    private $partner_id;
    private $partner_key;
    private $url = "https://thesieure.com/chargingws/v2";
    public function __construct()
    {
        $getTSR = TheSieuReService::getTSR();
        $this->partner_id = $getTSR->partner_id;
        $this->partner_key = $getTSR->partner_key;
    }

    public function getHistoryByUser()
    {
        $history = CardService::getHistoryByUser($this->user_id);
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$history,200);
    }

    public function requestCardTsr(Request $request)
    {
        $this->user = response()->json(Auth::guard()->user());
        $this->user_id = $this->user->getData()->id;
        //kiểm tra xem hệ thống nạp thẻ có bảo trì không
        $theSieuRe = TheSieuReService::getTSR();
        if($theSieuRe->status !== 0)
        {
            return FunResource::responseNoData(false,Mess::$SYSTEM_MAINTENANCE,401);
        }
        //lấy ra thông tin mệnh giá và nhà mạng mà người dùng gửi
        $face_value = FunResource::checkCard($request->telco,$request->declare_value);
        if(!$face_value){
            return FunResource::responseNoData(false,Mess::$CARD_NOT_EXIST,401);
        }
        //gởi thẻ lên server nếu bị trùng request_id thì thực hiện gởi lại
        do{
            $request_id = rand(11111111,99999999) + rand(22222222,88888888);
            $sign = md5($this->partner_key.$request->code.$request->serial);
            $card = [
                'telco' => $face_value['telco'],
                'serial' => $request->serial,
                'code' => $request->code,
                'amount' => $face_value['value'],
                'request_id' => $request_id,
                'partner_id' => $this->partner_id,
                'sign' => $sign,
                'command'=>'charging'
            ];

            //gửi card lên thesieure.com
            $respons = FunResource::requestDataPost($this->url,$card);
            //gửi thẻ thành công lên thẻ siêu rẻ thì thêm thẻ này vào database
            $result = json_decode($respons,true);
        }while($result['message'] == "REQUEST_ID_EXISTED");
        
        if($result['status'] < 100)
        {
            $insertCard = [
                'user_id' =>$this->user_id,
                'telco' => $request->telco,
                'declare_value'=>$request->declare_value,
                'fees' => $face_value['fees'],
                'penalty' => 0,
                'serial' => $request->serial,
                'code' => $request->code,
                'status' => $result['status']
            ];
            CardService::create($insertCard);
            return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
        }
        return FunResource::responseNoData(false,$result["message"],400);
    }
}
