<?php
namespace App\Http\Services;

use App\Models\TransHistory;
use Exception;
use Illuminate\Support\Facades\DB;

class TransHistoryService{
    public static function create($transHtr){
        // try{
            return TransHistory::create($transHtr);
        // }catch(Exception $e){
        //     return null;
        // }
    }
}
?>