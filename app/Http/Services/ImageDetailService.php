<?php
namespace App\Http\Services;

use App\Models\ImageDetail;
use Exception;
use Illuminate\Support\Facades\DB;

class ImageDetailService{
    public static function create($data){
        try{
            return DB::table('images_details')->insert($data);
        }catch(Exception $e){
            return;
        }
    }

    public static function getByAccount($account_id){
        try{
            return DB::table('images_details')->select('link_img')->where('account_id',$account_id)->get();
        }catch(Exception $e){
            return;
        }
    }
}

?>