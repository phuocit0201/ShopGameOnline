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
        return DB::table('account_game')
        ->join('categories','account_game.category_id','=','categories.id')
        ->select('account_game.*')
        ->where('account_game.status','!=',2)
        ->where('categories.status',0)
        ->paginate($perPage);
    }

    //dành cho admin vì lấy kèm những tài khoản trạng thái ẩn hoặc đã bán
    public static function getAccountByCategoryAdmin($id,$perPage,){
        return DB::table('account_game')
        ->join('categories','account_game.category_id','=','categories.id')
        ->select('account_game.*','categories.name')
        ->where('account_game.status','!=',2)
        ->where('account_game.category_id',$id)
        ->where('categories.status',0)
        ->paginate($perPage);
    }
    //dành cho user bình thường vì chỉ get những account trạng thái hiện
    public static function getAccountByCategoryClient($id,$perPage)
    {
        return DB::table('account_game')
        ->join('categories','account_game.category_id','=','categories.id')
        ->select('account_game.*','categories.name')
        ->where('account_game.status',0)
        ->where('account_game.category_id',$id)
        ->where('categories.status',0)
        ->paginate($perPage);
    }

    //get account game dành cho admin
    public static function getAccountByIdAdmin($id)
    {
        return DB::table('account_game')
        ->join('categories','account_game.category_id','=','categories.id')
        ->select('account_game.*')
        ->where('account_game.status','!=',2)
        ->where('account_game.id',$id)
        ->where('categories.status',0)
        ->first();
    }

    //get account game dành cho client
    public static function getAccountByIdClient($id)
    {
        return DB::table('account_game')
        ->join('categories','account_game.category_id','=','categories.id')
        ->select('account_game.*')
        ->where('account_game.status',0)
        ->where('account_game.id',$id)
        ->where('categories.status',0)
        ->first();
    }

    //update account
    public static function update($id,$data)
    {
        try{
            $account = AccountService::getAccountByIdAdmin($id);
            if($account){
                DB::table('account_game')->where('id',$id)->update($data);
                return true;
            }
            return false;
        }catch(Exception $e)
        {
            return false;
        }
    }
}

?>