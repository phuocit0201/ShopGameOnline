<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use Illuminate\Http\Request;
use App\Http\Services\CategoryService;
use Illuminate\Support\Facades\Validator;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return CategoryService::getAll($request->per_page);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required|max:50|alpha_num',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        }
        $category = CategoryService::create($request->all());
        if($category === null){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,401);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$category,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = CategoryService::find($id);
        if(!$category){
            return FunResource::responseNoData(false,Mess::$CATEGORY_NOT_EXIST,404);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$category,200);
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
    public function update(Request $request,$id)
    {
        //bắt lỗi người dùng gửi thông tin
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:50|alpha_num',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        }
        //khi sửa danh mục chỉ được sửa trạng thái ẩn hoặc hiện k được sửa thành trạng thái xóa
        if($request->status === 2)
        {
            return FunResource::responseNoData(false,Mess::$EXCEPTION,404);
        }
        $category = [
            'name' => $request->name,
            'status' => $request->status 
        ];

        //thực hiện cập nhật thông tin danh mục
        $update = CategoryService::update($id,$category);
        if(!$update){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,404);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,CategoryService::find($id),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $delete = CategoryService::delete($id);
       if($delete === false){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,401);
       }
       return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }
}
