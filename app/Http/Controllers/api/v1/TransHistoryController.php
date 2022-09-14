<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\TransHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransHistoryController extends Controller
{
    public function index(Request $request){
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,TransHistoryService::get($request->per_page),200);
    }
    public function getByUser(Request $request){
        $user =  response()->json(Auth::guard()->user());
        $idUser = $user->getData()->id;
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,TransHistoryService::getByUser($idUser,$request->per_page),200);
    }
}
