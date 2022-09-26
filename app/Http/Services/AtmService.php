<?php
namespace App\Http\Services;

use Exception;
use Illuminate\Support\Facades\DB;

class AtmService{
    public static function getAtm()
    {
        try{
            return DB::table('atm')
            ->join('banks','atm.bank_id','banks.id')
            ->select('atm.*','banks.bank_name','banks.link_logo')
            ->first();
        }catch(Exception $e){
            return null;
        }
    }
}
?>