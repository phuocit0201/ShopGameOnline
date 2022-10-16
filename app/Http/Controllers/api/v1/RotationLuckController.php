<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\RotationGiftService;
use App\Http\Services\RotationHistoryService;
use App\Http\Services\RotationLuckService;
use App\Http\Services\TransHistoryService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RotationLuckController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'rotation_name' => 'required|max:100',
            'img' => 'required',
            'price' => 'numeric|required',
            'slug' => 'required|max:255'
        ]);

        if($validator->fails()){
            return FunResource::responseData(false,Mess::$INVALID_INFO,$validator->errors()->toArray(),401);
        }
        $data = [
            'rotation_name' => $request->rotation_name,
            'img' => $request->img,
            'price' => $request->price,
            'slug' => $request->slug
        ];
        $rotationLuck = RotationLuckService::create($data);
        //thêm phần thưởng vào vòng quay
        if($rotationLuck){
            foreach($request->gifts as $key => $gift){
                $data = $gift;
                $data['rotation_id'] = $rotationLuck->id;
                RotationGiftService::create($data);
            }
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$rotationLuck,200);
    }

    public function index(){
        $rotationList = RotationLuckService::get();
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$rotationList,200);
    }

    public function showClient($slug){
        $rotationLuck = RotationLuckService::getBySlug($slug);
        if($rotationLuck && $rotationLuck->status != 0 || $rotationLuck == null){
            return FunResource::responseNoData(false,Mess::$NOT_FOUND,403);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$rotationLuck,200);
    }

    public function rotation(Request $request){
        $user_id = response()->json(Auth::guard()->user())->getData()->id;
        $user = UserService::getUserById($user_id);
        $slug = $request->slug;
        $rotationLuck = RotationLuckService::getBySlug($slug);
        if($user->money >= $rotationLuck->price){
            //xử lý khi người dùng quay
            $gifts = RotationGiftService::getByRotation($rotationLuck->id);
            $randomGift = random_int(1,100);
            $currentRatio = 0;
            $deg = 0;
            $gift = 0;
            for($i = 0; $i < count($gifts); $i++){
                $currentRatio += $gifts[$i]->ratio;
                if($randomGift <= $currentRatio){
                    $deg = (360 * 15) - ($i * 45) + random_int(5,20);
                    $gift = $gifts[$i]->coins;
                    break;
                }
            }

            //thêm vào lịch sử vòng quay
            $datahistoryRotation = [
                'coins' => $gift,
                'rotation_id' => $rotationLuck->id,
                'user_id' => $user_id
            ];

            $rotationHistory = RotationHistoryService::create($datahistoryRotation);
            if($rotationHistory){
                $dataUpdateUser = [
                    'money' => $user->money - $rotationLuck->price
                ];
                //thêm dữ liệu vào biến động số dư
                $transHistory = [
                    'action_id'=>$rotationHistory->id,
                    'action_flag' =>5,
                    'user_id'=> $user_id,
                    'after_money'=> $user->money,
                    'transaction_money'=> "-".$rotationLuck->price,
                    'befor_money'=> $dataUpdateUser['money'],
                    'note'=>"$rotationLuck->rotation_name",
                ];
                TransHistoryService::create($transHistory);
                $user->update($dataUpdateUser);
                return FunResource::responseData(true,Mess::$SUCCESSFULLY,[
                    'deg'=> $deg,
                    'coins'=> $gift
                ],200);
            }
            return FunResource::responseNoData(false,Mess::$REQUEST_FAILED,500);
            
        }else{
            return FunResource::responseNoData(false,Mess::$DO_NOT_ENOUGH_MONEY,401);
        }
       
    }

    public function getHistoryRotatoByUser(Request $request){
        $user_id = response()->json(Auth::guard()->user())->getData()->id;
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,RotationHistoryService::getByUser($user_id,$request->per_page),200);
    }

    public function getHistoryRecently($slug){
        $rotationLuck = RotationLuckService::getBySlug($slug);
        $historyRotationRecently = RotationHistoryService::getHistotyRecently($rotationLuck->id);
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$historyRotationRecently,200);
    }
}
