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
            $momo = MomoService::get();
            if($momo){
                $urlMomo = "http://localhost/test/FakeAPI/HistoryMomo.php?token=$momo->access_token";
                $resultMomo = json_decode(FunResource::requestGet($urlMomo),true);
                if($resultMomo){
                    foreach($resultMomo['momoMsg']['tranList'] as $key => $values)
                    {
                        $checkTransMomoExist = TransferService::checkTransExist($type_transfers[0],$values['tranId']);

                        if(!$values['comment'] || strpos($values['comment'],'NAPTIEN') === false || $checkTransMomoExist === 1 || $values['io'] != 1){
                            continue;
                        }
                       
                        $user_id = FunResource::parseComment('NAPTIEN',$values["comment"]);
                        $checkUserExist = UserService::getUserById($user_id);
                        //nếu user tồn tại thì tiến hành insert vào database và cộng tiền
                        if($checkUserExist){
                            $transfer = [
                                'user_id' => $user_id,
                                'type_transfer' => $type_transfers[0],
                                'tranding_code' => $values['tranId'],
                                'message' => $values['comment'],
                                'amount' =>$values['amount'],
                            ];

                            $insertTransMomo = TransferService::create($transfer);
                            if($insertTransMomo){
                                $transHistory = [
                                    'action_id'=>$insertTransMomo->id,
                                    'action_flag' =>4,
                                    'user_id'=> $user_id,
                                    'transaction_money'=> "+".$values['amount'],
                                    'note'=>'Nap tien MOMO',
                                ];
                                //cộng tiền cho user
                                FunResource::upMoneyForUser($transHistory);
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
                    $checkTransTSRExist = TransferService::checkTransExist($type_transfers[1],$values['tranId']);
                    if($checkTransTSRExist === 1 || $values['amount'][0] === '-' || strpos($values['description'],'NAPTIEN') === false){
                        continue;
                    }
                    $user_id = FunResource::parseComment('NAPTIEN',$values["description"]);
                    $checkUserExist = UserService::getUserById($user_id);
                    
                    //nếu user tồn tại thì cộng tiền cho user đó
                    if($checkUserExist)
                    {
                        $transfer = [
                            'user_id' => $user_id,
                            'type_transfer' => $type_transfers[1],
                            'tranding_code' => $values['tranId'],
                            'message' => $values['description'],
                            'amount' =>$values['amount'],
                        ];
                        //insert giao dịch vào db trigger sẽ tự động cộng tiền
                        $insertTransTSR = TransferService::create($transfer);
                        if($insertTransTSR){
                            $transHistory = [
                                'action_id'=>$insertTransTSR->id,
                                'action_flag' =>4,
                                'user_id'=> $user_id,
                                'transaction_money'=> "+".$values['amount'],
                                'note'=>'Nap tien the sieu re',
                            ];
                            //cộng tiền cho user đồng thời 
                            FunResource::upMoneyForUser($transHistory);
                        }
                    }
                }
            }
        }
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }
}
