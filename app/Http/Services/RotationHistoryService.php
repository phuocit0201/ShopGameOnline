<?php
namespace App\Http\Services;

use App\Models\RotationHistory;
use Exception;
use Illuminate\Support\Facades\DB;

class RotationHistoryService{
    public static function create($data)
    {
        try{
            return RotationHistory::create($data);
        }catch(Exception $e){
            return;
        }
    }
    public static function getByUser($id,$perPage){
        try{
            return DB::table('rotation_history')
            ->join('rotation_luck','rotation_history.rotation_id','=','rotation_luck.id')
            ->select('rotation_history.*','rotation_luck.rotation_name')
            ->where('user_id',$id)
            ->orderByDesc('id')
            ->paginate($perPage);
        }catch(Exception $e){
            return;
        }
    }

    public static function getHistotyRecently($rotationId){
        try{
            return DB::table('rotation_history')
            ->join('users','rotation_history.user_id','=','users.id')
            ->select('rotation_history.*','users.username')
            ->where('rotation_id',$rotationId)
            ->orderByDesc('id')
            ->take(10)
            ->get();
        }catch(Exception $e){
            return;
        }
    }
}
?>