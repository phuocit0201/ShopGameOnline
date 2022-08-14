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
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required|min:10|max:11|unique:momo',
            'full_name' => 'required',
            'access_token' => 'required'
        ]);
        if($validator->fails()){
            return FunResource::responseData(false,Mess::$INVALID_INFO,$validator->errors()->toArray(),400);
        }
        $data = [
            'phone_number' => $request->phone_number,
            'full_name' => $request->full_name,
            'access_token' => $request->access_token
        ];
        $momo = MomoService::create($data);
        if(!$momo){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,400);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$momo,200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required|min:10|max:11',
            'full_name' => 'required',
            'access_token' => 'required'
        ]);
        if($validator->fails()){
            return FunResource::responseData(false,Mess::$INVALID_INFO,$validator->errors()->toArray(),400);
        }
        $data = [
            'phone_number' => $request->phone_number,
            'full_name' => $request->full_name,
            'access_token' => $request->access_token
        ];
        $update = MomoService::update($id,$data);
        if(!$update){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,400);
        }
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }

    public function destroy($id){
        if(!MomoService::show($id)){
            return FunResource::responseNoData(false,Mess::$NOT_FOUND,400);
        }
        $delete = MomoService::destoy($id);
        if(!$delete){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,400);
        }
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }

    public static function show($id)
    {
        $momo = MomoService::show($id);
        if(!$momo){
            return FunResource::responseNoData(false,Mess::$NOT_FOUND,400);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$momo,200);
    }
}
