<?php
namespace App\Http\Services;

use App\Models\Transfer;
use Exception;
use Illuminate\Support\Facades\DB;

class TransferService{
    public static function create($transfer)
    {
        try{
            $insertTrans = Transfer::create($transfer);
            if(!$insertTrans){
                return null;
            }
            return $insertTrans;
        }catch(Exception $e){
            return null;
        }
       
    }

    public static function checkTransExist($type_trans,$tranId)
    {
        return DB::table('transfers')->where('type_transfer',$type_trans)
        ->where('tranding_code',$tranId)->count();
    }
}
?>