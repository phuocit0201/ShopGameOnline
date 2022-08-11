<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\CardService;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    private $partner_key = "4c5daff8a6f7e0ccf572421246915640";
    public function callbackTsr(Request $request)
    {
        $sign = md5($this->partner_key.$request->code.$request->serial);
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
}
