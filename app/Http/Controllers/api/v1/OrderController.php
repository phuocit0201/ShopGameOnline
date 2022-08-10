<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\AccountService;
use App\Http\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        //bắt lỗi người dùng gửi dữ liệu lên
        $validator = Validator::make($request->all(),[
            'account_id' => 'required|min:1|numeric',
        ]);
        if($validator->fails()){
            return FunResource::responseData(false,Mess::$INVALID_INFO,$validator->errors()->toArray(),404);
        }

        //kiểm xem nick mà người dùng mua hợp lệ hay không
        $account = AccountService::getAccountByIdClient($request->account_id);
        if(!$account){
            return FunResource::responseNoData(false,Mess::$ACCOUNT_NOT_EXIST,404);
        }

        //lấy thông tin người mua nick
        $user = response()->json(Auth::guard()->user());
        //nếu người dùng không đủ tiền mua nick thì báo lỗi
        if($user->getData()->money < $account->sale_price){
            return FunResource::responseNoData(false,Mess::$MONEY_NOT_ENOUGH,404);
        }

        //thực hiện giao dịch
        $data = [
            'user_id' => $user->getData()->id,
            'account_id' => $request->account_id,
            'status' => 0,
            'price' => $account->sale_price
        ];
        $order = OrderService::create($data);
        if(!$order){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,404);
        }
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }

    //xem lại đơn hàng khách hàng đã mua
    public function orderDetail($id)
    {
        $user = response()->json(Auth::guard()->user());
        $orderDetail = OrderService::orderDetail($id,$user->getData()->id);
        if(!$orderDetail){
            return FunResource::responseNoData(false,Mess::$ORDER_NOT_EXIST,404);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$orderDetail,200);
    }
}
