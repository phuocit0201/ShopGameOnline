<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\FaceValueService;
use App\Http\Services\TelcoService;
use App\Models\FaceValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaceValueController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'telco_id' => 'required|numeric',
            'price' => 'required|numeric',
            'fees' => 'required|numeric',
            'penalty' => 'required|numeric',
            'status' => 'required|numeric'
        ]);

        if($validator->fails()){
            return FunResource::responseData(false,Mess::$INVALID_INFO,$validator->errors()->toArray(),400);
        }
        //nếu không tìm thấy nhà mạng mà người dùng gởi lên thì báo lỗi
        if(!TelcoService::show($request->telco_id))
        {
            return FunResource::responseNoData(false,Mess::$TELCO_NOT_EXIST,400);
        }
        //nếu thẻ cào cần tạo đã có trên hệ thống thì báo lỗi
        if(FaceValueService::checkFaceValue($request->telco_id,$request->price) === 1)
        {
            return FunResource::responseNoData(false,Mess::$CARD_EXIST,400);
        }
        //kiểm tra mạnh giá thẻ hợp hay không
        if(FunResource::ErrorkPriceCard($request->price)){
            return FunResource::responseNoData(false,Mess::$INVALID_CARD_PRICE,400);
        }
        //thêm thẻ cào hợp lệ vào hệ thống
        $faceValue = FaceValueService::create($request->all());
        if(!$faceValue){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,400);
        }
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }

    public function destroy($id){
        if(!FaceValueService::show($id)){
            return FunResource::responseNoData(false,Mess::$CARD_NOT_EXIST,400);
        }
        if(!FaceValueService::update($id,["status"=>2])){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,400);
        }
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'status' => 'required|min:0|max:1|numeric'
        ]);
        if($validator->fails()){
            return FunResource::responseData(false,Mess::$INVALID_INFO,$validator->errors()->toArray(),400);
        }
        if(!FaceValueService::show($id)){
            return FunResource::responseNoData(false,Mess::$CARD_NOT_EXIST,400);
        }
        if(!FaceValueService::update($id,["status"=>1])){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,400);
        }
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }

    public function getByTelco($id){
        $cardList = FaceValueService::getByTelco($id);
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$cardList,200);
    }

    
}
