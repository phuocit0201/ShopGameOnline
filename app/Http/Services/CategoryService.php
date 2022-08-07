<?php
namespace App\Http\Services;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryService{

    public static function create($category)
    {
        return Category::create($category);
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
                return true;
            }
            return false;
        }catch(Exception $e){
            return false;
        }
    }
}
?>