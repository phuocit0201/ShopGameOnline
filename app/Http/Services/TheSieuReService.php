<?php
namespace App\Http\Services;

use Exception;
use Illuminate\Support\Facades\DB;

class TheSieuReService{
    public static function update($id,$data){
        try{
            DB::table('thesieure')->where('id',$id)->update($data);
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    public static function getTSR()
    {
        try{
            return DB::table('thesieure')->first();
        }catch(Exception $e){
            return null;
        }
    }
}
?>