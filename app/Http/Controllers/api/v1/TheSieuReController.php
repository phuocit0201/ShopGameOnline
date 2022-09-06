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
            'access_token' => 'required',
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
            'access_token' => $request->access_token,
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
}
