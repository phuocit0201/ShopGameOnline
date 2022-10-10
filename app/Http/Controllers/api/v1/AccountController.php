<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\AccountService;
use App\Http\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return AccountService::getAll($request->per_page);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //bắt lỗi thông tin người dùng gửi lên
        $validator = Validator::make($request->all(),[
            'class' => 'required|max:50',
            'server_game' => 'required|max:50',
            'level' => 'required|max:50',
            'family' => 'required|numeric|min:0|max:1|',
            'import_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'category_id' => 'required|numeric',
            'avatar' => 'required',
            'description' => 'required',
            'username' => 'required|max:50',
            'password' => 'required|max:50'
        ]);

        //kiểm tra nếu lỗi thì trả về những lỗi đó
        if($validator->fails()){
            return FunResource::responseData(false,Mess::$INVALID_INFO,$validator->errors()->toArray(),404);
        }

        $category = CategoryService::find($request->category_id);
        //trường hợp không tìm thấy category hoặc tìm thấy nhưng trạng thái là xóa 
        if($category === null || $category && $category->status === 2){
            return FunResource::responseNoData(false,Mess::$CATEGORY_NOT_EXIST,404);
        }
        $req = $request->all();
        //trường hợp thêm 1 account mà category đang ẩn thì ẩn luôn account đó
        if($category->status == 1){
            $req['status'] = 1;
        }
        //tiến hành insert vào database
        $account = AccountService::create($req);
        if($account === null){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,404);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$account,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAccountClient($id)
    {
        $account = AccountService::getAccountByIdClient($id);
        if(!$account){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,404);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$account,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'class' => 'required|max:50',
            'server_game' => 'required|max:50',
            'level' => 'required|max:50',
            'family' => 'required|numeric|min:0|max:1|',
            'import_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'category_id' => 'required|numeric',
            'description' => 'required',
            'avatar' => 'required',
            'status' => 'required|numeric|min:0|max:1|'
        ]);
        
        //kiểm tra nếu lỗi thì trả về những lỗi đó
        if($validator->fails()){
            return FunResource::responseData(false,Mess::$INVALID_INFO,$validator->errors()->toArray(),404);
        }
        $account = $request->all();
        unset($account["token"]);
        $update = AccountService::update($id,$account);
        if(!$update){
            return FunResource::responseNoData(false,Mess::$ACCOUNT_NOT_EXIST,404);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,AccountService::getAccountByIdAdmin($id),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = AccountService::update($id,["status"=>2]);
        if(!$delete){
            return FunResource::responseNoData(false,Mess::$ACCOUNT_NOT_EXIST,404);
        }
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }

    //get danh sách tài khoản game này giành cho client vì chi lấy những tài khoản có trạng thái hiện
    public function showAccountByCategoryClient(Request $request)
    {
        $search = [];
        if($request->class){
            $search['class'] = $request->class;
        }

        if($request->server_game){
            $search['server_game'] = $request->server_game;
        }

        if($request->family){
            $search['family'] = $request->family;
        }

        if($request->sale_price){
            switch($request->sale_price){
                case '1':
                    $search['sale_price'] = ['max'=>50000];
                    break;
                case '2':
                    $search['sale_price'] = ['min'=>50000,'max'=>200000];
                    break;
                case '3':
                    $search['sale_price'] = ['min'=>200000, 'max'=>500000];
                    break;
                case '4':
                    $search['sale_price'] = ['min'=>500000,'max'=>1000000];
                    break;
                case '5':
                    $search['sale_price'] = ['min'=>1000000];
                    break;
            }
        }

        $category = CategoryService::getIdCategoryBySlug($request->slug);
        $categoryId = $category->id;
        $accounts = AccountService::search($search,$request->per_page,$categoryId);
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$accounts,200);
    }

    public function CryptData(Request $request){
       
    }
}
