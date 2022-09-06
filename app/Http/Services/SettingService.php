<?php
namespace App\Http\Services;

use Exception;
use Illuminate\Support\Facades\DB;

class SettingService{
    public static function getSettings(){
        try{
            return DB::table('settings')->get();
        }catch(Exception $e){
            return null;
        }
    }

    public static function getValueSettings($key){
        try{
            return DB::table('settings')->where('key_name',$key)->first();
        }catch(Exception $e){
            return null;
        }
    }
}
?>