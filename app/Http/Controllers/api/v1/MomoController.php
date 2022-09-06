<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MomoController extends Controller
{
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required|min:10|max:11',
            'full_name' => 'required',
            'token_api' => 'required',
            'status' => 'min:0|max:1'
        ]);
        if($validator->fails()){
            return FunResource::responseData(false,Mess::$INVALID_INFO,$validator->errors()->toArray(),400);
        }
        $data = [
            'phone_number' => $request->phone_number,
            'full_name' => $request->full_name,
            'token_api' => $request->token_api,
            'status' => $request->status,
            'note' => $request->note
        ];
        $update = MomoService::update($data);
        if(!$update){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,400);
        }
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }
    public function show()
    {
        $momo = MomoService::get();
        if($momo->status != 0){
            return FunResource::responseNoData(false,Mess::$NOT_FOUND,400);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$momo,200);
    }
    public function edit()
    {
        $momo = MomoService::get();
        if(!$momo){
            return FunResource::responseNoData(false,Mess::$NOT_FOUND,400);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$momo,200);
    }
}
