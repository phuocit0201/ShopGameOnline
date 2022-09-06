<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\CardService;
use App\Http\Services\MomoService;
use App\Http\Services\SettingService;
use App\Http\Services\TheSieuReService;
use App\Http\Services\TransferService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    private $getTSR;
    private $rechargeMin;
    private $commandBank;
    //nhận dữ liệu callback từ thẻ siêu rẻ
    public function __construct()
    {
       $this->getTSR = TheSieuReService::getTSR();
       $this->rechargeMin = FunResource::site('recharge_min');
       $this->commandBank = FunResource::site('command_bank');
    }
    public function callbackTsr(Request $request)
    {
        $card = CardService::getCardByRequestId($request->code,$request->serial,$request->telco);
        //nếu không tồn tại thẻ này hoặc thẻ này đã được xử lý thì báo lỗi
        if(!$card || $card->status != 99){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,400);
        }
        $sign = md5($this->getTSR->partner_key.$card->code.$card->serial);

        if($sign === $request->callback_sign)
        {
            $data = [
                'value' => $request->value,
                'status' => $request->status
            ];
            CardService::update($request->telco,$request->serial,$request->code,$data);
            if($request->status === 1){
                //lấy ra thông tin mệnh giá thẻ cào
                $transHistory = [
                    'action_id'=>$card->id,
                    'action_flag' =>3,
                    'user_id'=> $card->user_id,
                    'transaction_money'=> "+".$request->amount,
                    'note'=>'Nap the cao',
                ];
                //cộng tiền cho user
                FunResource::upMoneyForUser($transHistory);
            }
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
                $urlMomo = "http://localhost/FakeAPI/HistoryMomo.php?token=$momo->access_token";
                $resultMomo = json_decode(FunResource::requestGet($urlMomo),true);
                if($resultMomo){
                    foreach($resultMomo['momoMsg']['tranList'] as $key => $values)
                    {
                        $checkTransMomoExist = TransferService::checkTransExist($type_transfers[0],$values['tranId']);

                        if($values['amount'] < $this->rechargeMin || !$values['comment'] || strpos($values['comment'],$this->commandBank) === false || $checkTransMomoExist === 1 || $values['io'] != 1){
                            continue;
                        }
                       
                        $user_id = FunResource::parseComment($this->commandBank,$values["comment"]);
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
        if($this->getTSR){
            $urlTSR = "http://localhost/FakeAPI/HistoryTSR.php?token=".$this->getTSR->access_token;
            $resultTSR = json_decode(FunResource::requestGet($urlTSR),true);
            if($resultTSR){
                foreach ($resultTSR['tranList'] as $key => $values)
                {
                    $checkTransTSRExist = TransferService::checkTransExist($type_transfers[1],$values['tranId']);
                    if((float) $values['amount'] < $this->rechargeMin || $checkTransTSRExist === 1 || strpos($values['description'],$this->commandBank) === false){
                        continue;
                    }
                    $user_id = FunResource::parseComment($this->commandBank,$values["description"]);
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
