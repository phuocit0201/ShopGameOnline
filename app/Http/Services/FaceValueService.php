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
            return DB::table('face_value')->join('telco','face_value.telco_id','=','telco.id')
            ->where('telco.status','!=',2)
            ->where('id',$id)
            ->where('face_value.status','!=',2)
            ->first();
        }catch(Exception $e){
            return null;
        }
    }

    //kiểm tra xem cái thẻ này đã tồn tại trên database chưa
    public static function checkFaceValue($telco_id,$price)
    {
        return DB::table('face_value')->join('telco','face_value.telco_id','=','telco.id')
        ->where('face_value.telco_id',$telco_id)
        ->where('telco.status','!=',2)
        ->where('face_value.status','!=',2)
        ->where('face_value.price',$price)->count();
    }

    public static function getByTelco($telco_id){
        try{
            return DB::table('face_value')->join('telco','face_value.telco_id','=','telco.id')
            ->select('face_value.*')
            ->where('face_value.telco_id',$telco_id)
            ->where('telco.status',0)
            ->where('face_value.status',0)
            ->get();
        }catch(Exception $e){
            return null;
        }
    }
}

?>