<?php
namespace App\Http\Services;

use App\Models\Transfer;
use Illuminate\Support\Facades\DB;

class TransferService{
    public static function create($transfer)
    {
        Transfer::create($transfer);
    }

    public static function checkTransExist($type_trans,$tranId)
    {
        return DB::table('transfers')->where('type_transfer',$type_trans)
        ->where('tranding_code',$tranId)->count();
    }
}
?>