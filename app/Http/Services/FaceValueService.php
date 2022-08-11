<?php
namespace App\Http\Services;

use App\Models\FaceValue;
use Exception;
use Illuminate\Support\Facades\DB;

class FaceValueService{
    public static function create($faceValue)
    {
        try{
            return FaceValue::create($faceValue);
        }catch(Exception $e){
            return null;
        }
    }

    public static function update($id,$faceValue)
    {
        try{
            DB::table('face_value')->where('id',$id)->update($faceValue);
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    public static function show($id)
    {
        try{
            return DB::table('face_value')->where('id',$id)->where('status',0)->first();
        }catch(Exception $e){
            return null;
        }
    }
}

?>