<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\CardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function getHistoryByUser()
    {
        $user = response()->json(Auth::guard()->user());
        $history = CardService::getHistoryByUser($user->getData()->id);
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$history,200);
    }
}
