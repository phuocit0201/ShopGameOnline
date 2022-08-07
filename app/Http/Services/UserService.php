<?php
namespace App\Http\Services;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;

class UserService{

    //tạo mới người dùng
    public static function createUser($user)
    {
        try{
            return User::create($user);
        }catch(Exception $e){
            return;
        }
    }

    //get all user
    public static function getAllUser($perPage)
    {
        try{
            return new UserCollection(User::paginate($perPage));
        }catch(Exception $e){
            return null;
        }
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
        try{
            $user = UserService::getUserById($id);
            if($user){
                $user->delete();
                return true;
            }
        }catch(Exception $e){
            return false;
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
            return null;
        }   
    }
}
?>