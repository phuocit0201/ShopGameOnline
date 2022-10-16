<?php

namespace App\Http\Helpers;

class Mess{
    public static $EMAIL_EXIST = "This email already exists on the system";
    public static $USERNAME_EXIST = "This username already exists on the system";
    public static $SUCCESSFULLY = "Successfully";
    public static $LOGIN_FAILED = "Incorrect account or password";
    public static $EXCEPTION = "An error occurred during execution. Please try again";
    public static $UNAUTHORIZED = "You are not authorized to use this function";
    public static $TOKEN_FAILED = "Token not exist. Please try again";
    public static $USER_NOT_EXIST = "This user not exist on the system";
    public static $BANNED_USER = "Your account has been locked for creasons ";
    public static $REQUEST_FAILED = "Your request was not processed. Please try again";
    public static $PASSWORD_OLD_INCORRECT = "Old password is incorrect";
    public static $INVALID_INFO = "Invalid information";
    public static $CONFIRM_PASSWORD_INCORRECT = "Confirm password is incorrect";
    public static $CATEGORY_NOT_EXIST = "This category not exist";
    public static $ACCOUNT_NOT_EXIST = "This account not exist on the system";
    public static $MONEY_NOT_ENOUGH = "The amount in your account is not enough! Please top up";
    public static $DOWN_MOENY_ERROR = "Reduction amount must be less than existing amount";
    public static $ORDER_NOT_EXIST = "This order not exist";
    public static $CARD_NOT_EXIST = "Loại thẻ này không hợp lệ vui lòng thử lại";
    public static $INVALID_SIGNATURE = "Invalid signature";
    public static $TELCO_NOT_EXIST ="This telco not exists on the system";
    public static $CARD_EXIST ="This card already exists on the system";
    public static $INVALID_CARD_PRICE = "Invalid card price";
    public static $NOT_FOUND = "This information could not be found";
    public static $SYSTEM_MAINTENANCE_CARD = "Hệ thống nạp thẻ đang bảo trì vui lòng quay lại sau";
    public static $DO_NOT_ENOUGH_MONEY = "Số dư của bạn không đủ vui lòng nạp thêm tiền";
    public static function messageValidation(){
        return [
            'email.unique' => 'Email này đã tồn tại trên hệ thống',
            'email.email' => 'Email không đúng định dạng',
            'email.required' => 'Email không được bỏ trống',
            'email.max:100' => 'Email tối đa 100 kí tự',
            'username.unique' => 'Tài khoản này đã tồn tại trên hệ thống'
        ];
    }
}

?>