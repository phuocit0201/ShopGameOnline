<?php
namespace App\Http\Services;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Helpers\Mess;
use Exception;
use App\Http\Helpers\FunResource;
use Illuminate\Support\Facades\Auth;

class UserService{

    //tạo mới người dùng
    public static function createUser($data)
    {
        try{
            return User::create($data);
        }catch(Exception $e){
            return;
        }
    }

    //get all user
    public static function getAllUser($perPage)
    {
        return new UserCollection(User::paginate($perPage));
    }

    //get user by id
    public static function getUserById($id){
        try{
            return new UserResource(User::findOrFail($id));
        }catch(Exception $e){
            return;
        }
    }

    //xóa 1 tài khoản người dùng

    public static function deleteUser($id){
        $user = UserService::getUserById($id);
        if($user){
            $user->delete();
            return true;
        }
        return false;
    }

    //lấy thông tin 1 tài khoản bằng username
    public static function getUserByUsername($username)
    {
        try{
            $checkBanned = DB::table("users")->where('username',$username)->first();
                return $checkBanned;
        }catch(Exception $e){
        }   
        return null;
    }
}
?>