<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function unauthorized()
    {
        return FunResource::responseNoData(false,Mess::$UNAUTHORIZED,401);
    }

    public function tokenNotExist()
    {
        return FunResource::responseNoData(false,Mess::$TOKEN_FAILED,401);
    }

    public function securityRequest()
    {
        return FunResource::responseNoData(false,Mess::$REQUEST_FAILED,401);
    }

    public function keyWebsiteFailed()
    {
        return FunResource::responseNoData(false,Mess::$REQUEST_FAILED,401);
    }
}
