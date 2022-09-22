<?php
namespace App\Http\Services;

use App\Models\TransHistory;
use Exception;
use Illuminate\Support\Facades\DB;

class TransHistoryService{
    public static function create($transHtr){
        try{
            return TransHistory::create($transHtr);
        }catch(Exception $e){
            return null;
        }
    }
    public static function get($perPage){
        try{
            return DB::table('transaction_history')
            ->orderByDesc('id')
            ->paginate($perPage);
        }catch(Exception $e){
            return null;
        }
    }
    public static function getByUser($id,$perPage){
        try{
            return DB::table('transaction_history')->where('user_id',$id)
            ->orderByDesc('id')
            ->paginate($perPage);
        }catch(Exception $e){
            return null;
        }
    }
}
?>