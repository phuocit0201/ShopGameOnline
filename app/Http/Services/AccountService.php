<?php
namespace App\Http\Services;

use App\Models\Account;
use Exception;
use Illuminate\Support\Facades\DB;

class AccountService{
    public static function create($account){
        try{
            return Account::create($account);
        }catch(Exception $e){
            return null;
        }
    }

    public static function getAll($perPage){
        return DB::table('account_game')->where('status','!=',2)->paginate($perPage);
    }

    public static function getByCategory($id,$perPage){
        return DB::table('account_game')
        ->join('categories','account_game.category_id','=','categories.id')
        ->select('account_game.*','categories.name')
        ->where('account_game.status','!=',2)
        ->where('account_game.category_id',$id)
        ->paginate($perPage);
    }
}

?>