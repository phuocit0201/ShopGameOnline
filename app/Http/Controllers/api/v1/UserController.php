<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Requests\UsersRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\UserService;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\TransHistoryService;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => 
        [
            'login','getMe','logout','updateMoney','refreshToken',
            'index','show','create','destroy','update','changePassword'
        ]]);
        
    }

    public function index(Request $request){
        return UserService::getAllUser($request->per_page);
    }

    public function create(Request $request)
    {
        $data = [
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password,
            'confirmPassword' => $request->confirmPassword,
            'name' => $request->name
        ];

        $validator = Validator::make($data,[
                'email' => 'required|email|unique:users|max:100',
                'username' => 'required|unique:users|min:6|max:50|alpha_num',
                'password' => 'required|min:6|max:50|alpha_num',
                'name'=> 'required|max:50',
                'confirmPassword' => 'required|min:6|max:50|alpha_num'
        ]);
        $errors = $validator->errors()->toArray();
        if($request->password !== $request->confirmPassword){
            $errors['confirmPassword'] = ['Xác nhận mật khẩu không đúng'];
        }
        //kiểm tra có lỗi ở những trường hợp trên hay không
        if ($errors){
            return response()->json([
                'status' => false,
                'errors' => $errors
            ]);
        }
        //lấy ip người dùng
        $data["ip"] = $request->getClientIp();
        //mã hóa mật khẩu
        $data["password"] = Hash::make($request->password);

        //thực hiện tạo tài khoản trong database
        $user =  UserService::createUser($data);
        //báo lỗi khi thêm người dùng vào database thất bại
        if(!$user)
        {
            return FunResource::responseNoData(false,Mess::$EXCEPTION,401);
        }
        //đăng kí thành công
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$user,200);
    }

    public function show($id)
    {
        return UserService::getUserById($id);       
    }

    public function changePassword(Request $request)
    {
        //lấy ra thông tin người dùng yêu cầu cập nhật thông tin cá nhân
        $getMe = response()->json(Auth::guard()->user());
        $me = UserService::getUserById($getMe->getData()->id);
        $user = [
            'password_old' => $request->password_old,
            'password_new' => $request->password_new,
            'password_confirm' => $request->password_confirm
        ];

        //bắt lỗi người dùng nhập mật khẩu
        $validator = Validator:: make($user,[
            'password_old' => 'required|min:6|max:50|alpha_num',
            'password_new' => 'required|min:6|max:50|alpha_num',
            'password_confirm' => 'required|min:6|max:50|alpha_num',
        ]);
        $errors = $validator->errors()->toArray();
         //bắt lỗi nếu xác nhận mật khẩu không khớp
        if($user['password_new'] !== $user['password_confirm']){
            $errors['password_confirm'] = ['Xác nhận mật khẩu không khớp'];
        }
        //kiểm tra xem người dùng nhập mật khẩu cũ chính xác không
        if(!Hash::check($user["password_old"],$me["password"]))
        {
            $errors['password_old'] = ['Mật khẩu cũ không đúng'];
        }
        if($errors){
            return response()->json([
                'status' => false,
                'errors' => $errors
            ]);
        }
        //thực hiện thay đổi mật khẩu đồng thời xóa jwt cũ cấp lại jwt mới cho người dùng
        try{
            $me->update(['password'=>Hash::make($user["password_new"])]);
            //hủy jwt cũ
            Auth::logout();
            //cấp lại jwt mới
            $username = $getMe->getData()->username;
            $password = $user['password_new'];
            $token = Auth::attempt(['username'=>$username,'password'=>$password]);
            return FunResource::respondWithToken($token);
        }catch(Exception $e){
            return FunResource::responseNoData(false,Mess::$EXCEPTION,401);
        }
    }

    public function update(Request $request,$id)
    {
        $data = [
            'name' => $request->name,
            'role' => $request->role,
            'banned' => $request->banned,
            'reason_banned' => $request->reason_banned,
        ];

        $validator = Validator::make($data,[
                'role' => 'required|max:1|min:0|numeric',
                'banned' => 'required|min:0|max:1|numeric',
                'name'=> 'required|max:50',
        ]);

        //kiểm tra có lỗi ở những trường hợp trên hay không
        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        }

        $user = UserService::getUserById($id);
        if(!$user){
           return FunResource::responseNoData(false,Mess::$USERNAME_EXIST,401);
        }
        $user->update($data);
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,UserService::getUserById($id),200);
    }

    public function updateMoney(Request $request)
    {
        $validator = Validator::make($request->all(),[
                'id' => 'required|numeric',
                'money' => 'required|min:1|numeric',
                'action' => 'required|min:0|max:1|numeric',
                'note' => 'required|max:100'
        ]);

        //kiểm tra có lỗi ở những trường hợp trên hay không
        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        }
        //kiểm tra xem user cần cộng tiền có hợp lệ hay không
        $user = UserService::getUserById($request->id);
        if(!$user || $user->banned === 1){
           return FunResource::responseNoData(false,Mess::$USER_NOT_EXIST,401);
        }

        //tạo dữ liệu biến động số dư của user
        $transHtr = [
            'user_id' => $request->id,
            'action_id' => 0,
            'action_flag' =>2,
            'after_money' => $user->money,
            'note' => $request->note
        ];

        //dữ liệu update
        $data = [
            'id' => $request->id
        ];
        //nếu action = 0 thì tiến hành cộng tiền
        if($request->action === 0)
        {
            $data['money'] = $user->money + $request->money;
            $user->update($data);
            //sau khi cộng tiền xong thì thêm vào biến động số dư của user đó
            $transHtr['transaction_money'] = "+".$request->money;
            $transHtr['befor_money'] = $data["money"];
            TransHistoryService::create($transHtr);
        }
        //nếu action = 1 thì trừ tiền
        else if($request->action === 1){
            //nếu số tiền trừ lớn hơn số tiền hiện có thì báo lỗi
            if($request->money > $user->money)
            {
                return FunResource::responseNoData(false,Mess::$DOWN_MOENY_ERROR,404);
            }
            $data["money"] = $user->money - $request->money;
            $user->update($data);
            $transHtr['transaction_money'] = "-".$request->money;
            $transHtr['befor_money'] = $data["money"];
            TransHistoryService::create($transHtr);
        }
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }

    public function login(Request $request)
    {
        //lấy ra thông tin user trong database với username mà người dùng gởi request

        $user = UserService::getUserByUsername($request->username);
        if($user){
            //nếu người dùng đăng nhập đúng và tài khoản bị khóa thì trả về thông báo lý do khóa
            if(Hash::check($request->password,$user->password) && $user->banned === 1){
                return FunResource::responseNoData(false,Mess::$BANNED_USER.$user->reason_banned,401);
            }
        }

        //nếu người dùng hợp lệ thì thực hiện đăng nhập
        $token = Auth::attempt($request->all());
        if (!$token) {
            return FunResource::responseNoData(false,Mess::$LOGIN_FAILED,401);
        }
        
        return FunResource::respondWithToken($token);
    }

    public function getMe()
    {
        return response()->json(Auth::guard()->user());
    }

    public function logout()
    {
        Auth::logout();
        return FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    }

    public function refreshToken(Request $request)
    {
        return $request->server('HTTP_USER_AGENT');
        return FunResource::respondWithToken(Auth::parseToken()->refresh());
    }
    // public function destroy($id)
    // {
    //     if(UserService::deleteUser($id))
    //     {
    //         FunResource::responseNoData(true,Mess::$SUCCESSFULLY,200);
    //     }
    //     return FunResource::responseNoData(false,Mess::$USER_NOT_EXIST,401);
    // }
}
