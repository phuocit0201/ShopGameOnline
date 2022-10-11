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
        ->select('account_game.class','account_game.family','account_game.id','account_game.sale_price','account_game.server_game','account_game.level','account_game.created_at','account_game.description','account_game.category_id')
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

    //search account
    public static function search($search,$perPage,$id)
    {
        $query = Account::query();
        $query->join('categories','account_game.category_id','=','categories.id');
        $query->select('account_game.class','account_game.family','account_game.id','account_game.sale_price','account_game.server_game','account_game.level','account_game.avatar');
        try{
            foreach($search as $key => $value){
                if($key == 'sale_price'){
                    if(isset($value['min'])){
                        $query->where($key,'>=',$value['min']);
                    }

                    if(isset($value['max'])){
                        $query->where($key,'<=',$value['max']);
                    }
                }else{
                    $query->where($key,$value);
                }
            }
            $query->where('account_game.status',0);
            $query->where('account_game.category_id',$id);
            $query->where('categories.status',0);
            return $query->paginate($perPage);
        }catch(Exception $e){
            return null;
        }
    }

    public static function getRelatedAccount($data){
        // try{
            return DB::table('account_game')
            ->join('categories','account_game.category_id','=','categories.id')
            ->select('account_game.class','account_game.family','account_game.id','account_game.sale_price','account_game.server_game','account_game.level','account_game.avatar')
            ->where('account_game.status',0)
            ->where('categories.status',0)
            ->where('account_game.class',$data['class'])
            ->where('account_game.server_game',$data['server_game'])
            ->where('account_game.sale_price','<=',$data['sale_price'] + 200000)
            ->where('account_game.sale_price','>=',$data['sale_price'] - 200000)
            ->where('account_game.sale_price','>',0)
            ->where('account_game.id','!=',$data['id'])
            ->where('categories.id',$data['category_id'])
            ->get();
        // }catch(Exception $e){
        //     return;
        // }
    }
}

?>