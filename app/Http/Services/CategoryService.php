<?php
namespace App\Http\Services;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryService{
    public static function create($category)
    {
        try{
            return Category::create($category);
        }catch(Exception $e){
            return null;
        }
    }

    public static function getAll()
    {
        return DB::table('categories')->where('status','!=',2)
        ->get();
    }

    public static function getAllByClient()
    {
        return DB::table('categories')
        ->where('status',0)
        ->get();
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

    public static function getIdCategoryBySlug($slug){
        try{
            $category = DB::table('categories')->where('slug',$slug)->first();
            return $category;
        }catch(Exception $e){
            return null;
        }
    }

}
?>