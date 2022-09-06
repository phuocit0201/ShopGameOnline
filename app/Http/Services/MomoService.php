<?php
namespace App\Http\Services;

use App\Models\Momo;
use Exception;
use Illuminate\Support\Facades\DB;

class MomoService{
    public static function update($data)
    {
        try{
            DB::table('momo')->update($data);
            return true;
        }catch(Exception $e){
            return false;
        }
    }

    public static function get()
    {
        try{
            return DB::table('momo')->first();
        }catch(Exception $e)
        {
            return null;
        }
    }
}
?>