<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\TheSieuReService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TheSieuReController extends Controller
{
    public function update($id,Request $request)
    {
        // $dt = new DateTime();
        // return $dt->format('Y-m-d H:i:s');
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'full_name' => 'required',
            'token_api' => 'required',
            'partner_key' => 'required',
            'partner_id' => 'required',
            'status_bank' => 'min:0|max:1',
            'status_card' => 'min:0|max:1',
        ]);
        if($validator->fails()){
            return FunResource::responseNoData(false,Mess::$INVALID_INFO,401);
        }
        $data = [
            'username' => $request->username,
            'full_name' => $request->full_name,
            'token_api' => $request->token_api,
            'partner_key' => $request->partner_key,
            'partner_id' => $request->partner_id,
            'status_bank' => $request->status_bank,
            'status_card' => $request->status_card
        ];
        $update = TheSieuReService::update($id,$data);
        if(!$update){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,401);
        }
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }

    public function getTsrBank()
    {
        $theSieuRe = TheSieuReService::getTSR();
        if($theSieuRe->status_bank != 0)
        {
            return FunResource::responseNoData(false,Mess::$NOT_FOUND,400);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$theSieuRe,200);
    }
    public function getTsrCard()
    {
        $theSieuRe = TheSieuReService::getTSR();
        if($theSieuRe->status_card != 0)
        {
            return FunResource::responseNoData(false,Mess::$NOT_FOUND,400);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$theSieuRe,200);
    }
    public function edit()
    {
        $theSieuRe = TheSieuReService::getTSR();
        if(!$theSieuRe->status_card)
        {
            return FunResource::responseNoData(false,Mess::$NOT_FOUND,400);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$theSieuRe,200);
    }
}
