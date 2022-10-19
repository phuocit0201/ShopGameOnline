<?php
namespace App\Http\Services;

use App\Models\RotationLuck;
use Exception;
use Illuminate\Support\Facades\DB;

class RotationLuckService{
    public static function create($data){
        try{
            return RotationLuck::create($data);
        }catch(Exception $e){
            return;
        }
    }

    public static function getClient(){
        try{
            return DB::table('rotation_luck')
            ->leftJoin('rotation_history','rotation_luck.id','=','rotation_history.rotation_id')
            ->selectRaw('count(rotation_history.id) as sum,rotation_luck.id,rotation_luck.price,rotation_luck.rotation_name,rotation_luck.img,rotation_luck.slug,rotation_luck.img_gift')
            ->groupByRaw('rotation_luck.id,rotation_luck.price,rotation_luck.rotation_name,rotation_luck.img,rotation_luck.slug,rotation_luck.img_gift')
            ->where('rotation_luck.status',0)
            ->get();
        }catch(Exception $e){
            return;
        }
    }

    public static function getBySlug($slug){
        try{
            return DB::table('rotation_luck')
            ->where('slug',$slug)->first();
        }catch(Exception $e){
            return;
        }
    }
}

?>