<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\CardService;
use App\Http\Services\MomoService;
use App\Http\Services\TheSieuReService;
use App\Http\Services\TransferService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    private $partner_key = "4c5daff8a6f7e0ccf572421246915640";
    //nhận dữ liệu callback từ thẻ siêu rẻ
    public function callbackTsr(Request $request)
    {
        $card = CardService::getCardByRequestId($request->request_id);
        //nếu không tồn tại thẻ này hoặc thẻ này đã được xử lý thì báo lỗi
        if(!$card || $card->status != 99){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,400);
        }
        $sign = md5($this->partner_key.$card->code.$card->serial);

        if($sign === $request->callback_sign)
        {
            $data = [
                'value' => $request->value,
                'status' => $request->status
            ];
            CardService::update($request->request_id,$data);
            return FunResource::responseNoData(true,"thanks",200);
        }
        return FunResource::responseNoData(false,Mess::$INVALID_SIGNATURE,400);
    }
    //lấy lịch sử giao dịch momo,atm từ api.web2m.com
    public function getHistoryTrans()
    {
        $type_transfers = ['MOMO','THESIEURE'];
        //xử lý lịch sử giao dịch momo
        $momoList = MomoService::getAll();
        if($momoList){
            foreach($momoList as $key => $historyMomo)
            {
                $urlMomo = "http://localhost/test/FakeAPI/HistoryMomo.php?token=$historyMomo->access_token";
                $resultMomo = json_decode(FunResource::requestGet($urlMomo),true);
                if($resultMomo){
                    foreach($resultMomo['momoMsg']['tranList'] as $key => $values)
                    {
                        //nếu giao dịch này chưa có trong database mới thực hiện insert vào db
                        $checkTransMomoExist = TransferService::checkTransExist($type_transfers[0],$values['tranId']);
                        $user_id = explode(' ',$values['comment'])[1];
                        $command = explode(' ',$values['comment'])[0];
                        $checkUserExist = UserService::getUserById($user_id);
                        //nếu lịch sử này chưa có trong db và user cộng tiền tồn tại và cú pháp nạp tiền đúng
                        if($checkTransMomoExist === 0 && $checkUserExist && $values['io'] == 1 && strtoupper($command) === "NAPTIEN"){
                            $transfer = [
                                'user_id' => $user_id,
                                'type_transfer' => $type_transfers[0],
                                'tranding_code' => $values['tranId'],
                                'message' => $values['comment'],
                                'amount' =>$values['amount'],
                            ];
                            //insert giao dịch vào db trigger sẽ tự động cộng tiền
                            TransferService::create($transfer);
                        }
                        
                    }
                }
            }
        }
        
        //xử lý lịch sử giao dịch thẻ siêu rẻ
        $thesieure = TheSieuReService::getTSR();
        if($thesieure){
            $urlTSR = "http://localhost/test/FakeAPI/HistoryTSR.php?token=$thesieure->access_token";
            $resultTSR = json_decode(FunResource::requestGet($urlTSR),true);
            if($resultTSR){
                foreach ($resultTSR['tranList'] as $key => $values)
                {
                    $user_id = explode(' ',$values['description'])[1];
                    $command = explode(' ',$values['description'])[0];
                    $checkUserExist = UserService::getUserById($user_id);
                    $checkTransTSRExist = TransferService::checkTransExist($type_transfers[1],$values['tranId']);
                    //nếu giao dịch chưa có trong db và user cộng tiền tồn tại và số tiền không âm và lệnh cồn tiền đúng
                    if($checkTransTSRExist === 0 && $checkUserExist && $values['amount'][0] != '-' && strtoupper($command) === "NAPTIEN")
                    {
                        $transfer = [
                            'user_id' => $user_id,
                            'type_transfer' => $type_transfers[1],
                            'tranding_code' => $values['tranId'],
                            'message' => $values['description'],
                            'amount' =>$values['amount'],
                        ];
                        //insert giao dịch vào db trigger sẽ tự động cộng tiền
                        TransferService::create($transfer);
                    }
                }
            }
        }
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }
}
