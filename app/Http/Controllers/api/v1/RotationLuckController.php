<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\RotationGiftService;
use App\Http\Services\RotationLuckService;
use App\Http\Services\UserService;
use App\Models\RotationLuck;
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
        $user =  response()->json(Auth::guard()->user());
        $user_id = $user->getData()->id;
        $slug = $request->slug;
        $user = UserService::getUserById($user_id);
        $rotationLuck = RotationLuckService::getBySlug($slug);
        if($user->money >= $rotationLuck->price){
            $gifts = RotationGiftService::getByRotation($rotationLuck->id);
            $randomGift = random_int(1,100);
            $currentRatio = 0;
            for($i = 0; $i < count($gifts); $i++){
                $currentRatio += $gifts[$i]->ratio;
                if($randomGift <= $currentRatio){
                    $deg = (360 * 15) - ($i * 45) + random_int(5,20);
                    return FunResource::responseData(true,Mess::$SUCCESSFULLY,[
                        'deg'=>$deg,
                        'coins'=> $gifts[$i]->coins
                    ],200);
                }
            }
        }else{
            return FunResource::responseNoData(false,Mess::$REQUEST_FAILED,401);
        }
       
    }
}
