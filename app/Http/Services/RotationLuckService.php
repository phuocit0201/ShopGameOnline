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

    public static function get(){
        try{
            return RotationLuck::all();
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