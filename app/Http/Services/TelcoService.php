<?php
namespace App\Http\Services;

use App\Models\Telco;
use Exception;
use Illuminate\Support\Facades\DB;

class TelcoService{
    public static function show($id)
    {
        try{
            return DB::table('telco')->where('status',0)->find($id);
        }catch(Exception $e){
            return null;
        }
    }
}
?>