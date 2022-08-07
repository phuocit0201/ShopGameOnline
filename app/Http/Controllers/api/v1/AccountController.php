<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\AccountService;
use App\Http\Services\CategoryService;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
            'info1' => 'required|max:50|alpha_num',
            'info2' => 'required|max:50|alpha_num',
            'info3' => 'required|max:50|alpha_num',
            'import_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'category_id' => 'required|numeric',
            'description' => 'required'
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
    public function show($id)
    {
        
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showByCategory(Request $request,$id)
    {
        return AccountService::getByCategory($id,$request->per_page);
    }
}
