<?php
namespace App\Http\Services;

use App\Models\RotationGift;
use Exception;
use Illuminate\Support\Facades\DB;

class RotationGiftService{
    public static function create($data){
        try{
            return RotationGift::create($data);
        }catch(Exception $e){
            return;
        }
    }

    public static function getByRotation($rotationId){
        try{
            return DB::table('rotation_gifts')
            ->where('rotation_id',$rotationId)
            ->get();
        }catch(Exception $e){
            return;
        }
    }
}
?>