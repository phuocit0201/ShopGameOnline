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

    public static function getTopMonth(){
        try{
            return DB::table('users')
            ->join('transaction_history','users.id','transaction_history.user_id')
            ->selectRaw('users.username, sum(substr(transaction_history.transaction_money,2)) as tong')
            ->whereRaw('transaction_history.action_flag = 3 or transaction_history.action_flag = 4 and year(transaction_history.created_at) = SUBSTRING(current_date(),1 , 4) and month(transaction_history.created_at) = SUBSTRING(current_date(),6 , 2)')
            ->groupBy('users.username')
            ->orderByDesc('tong')
            ->take(3)
            ->get();
        }catch(Exception $e){
            return;
        }
    }
}
?>