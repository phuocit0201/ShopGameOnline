<?php
namespace App\Http\Services;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryService{
    private $delete = 2;


    public static function create($category)
    {
        try{
            return Category::create($category);
        }catch(Exception $e){
            return null;
        }
    }

    public static function getAll($perPage)
    {
        return DB::table('categories')->where('status','!=',2)->paginate($perPage);
    }

    public static function find($id){
        return DB::table('categories')->where('status','!=',2)->where('id',$id)->first();
    }

    public static function delete($id)
    {
        try{
            $category = CategoryService::find($id);
            if($category !== null){
                DB::table('categories')->where('id',$id)->update(['status'=>2]);
                CategoryService::statusAccountByCategory($id,2);
                return true;
            }
            return false;
        }catch(Exception $e){
            return false;
        }
    }

    public static function update($id,$data)
    {
        try{
            $category = CategoryService::find($id);
            if($category){
                DB::table('categories')->where('id',$id)->update($data);
                if($data['status'] === 1){
                    CategoryService::statusAccountByCategory($id,1);
                }
                return true;
            }
            return false;
        }catch(Exception $e){
            return false;
        }
    }

    public static function statusAccountByCategory($id,$status)
    {
        try{
            DB::table('account_game')->where('category_id',$id)->update(['status'=>$status]);
            return;
        }catch(Exception $e){
            return;
        }
    }

}
?>