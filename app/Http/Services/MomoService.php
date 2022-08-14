<?php
namespace App\Http\Services;

use App\Models\Momo;
use Exception;
use Illuminate\Support\Facades\DB;

class MomoService{
    public static function create($momo)
    {
        try{
            return Momo::create($momo);
        }catch(Exception $e){
            return null;            
        }
    }

    public static function update($id,$data)
    {
        try{
            DB::table('momo')->where('id',$id)->update($data);
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    public static function destoy($id)
    {
        try{
            DB::table('momo')->delete($id);
            return true;
        }catch(Exception $e)
        {
            return false;
        }
    }

    public static function show($id)
    {
        try{
            return DB::table('momo')->where('id',$id)->first();
        }catch(Exception $e){
            return null;
        }
    }
}
?>