<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FunResource;
use App\Http\Helpers\Mess;
use App\Http\Services\AtmService;
use App\Http\Services\MomoService;
use App\Http\Services\TheSieuReService;
use Illuminate\Http\Request;

class AtmWalletController extends Controller
{
    public function index()
    {
        $momo = MomoService::get();
        $atm = AtmService::getAtm();
        $tsr = TheSieuReService::getTSR();
        $listAtmWallet = [
            'command' => FunResource::site('command_bank'),
            'data'=>[]
        ];
        if($momo && $momo->status === 0){
            array_push($listAtmWallet['data'],[
                'account_number' => $momo->phone_number,
                'link_logo' => $momo->link_logo,
                'full_name' => $momo->full_name,
                'note' => $momo->note,
                'type' => 'momo'
            ]);
        }
        if($atm && $atm->status === 0){
            array_push($listAtmWallet['data'],[
                'account_number' => $atm->account_number,
                'link_logo' => $atm->link_logo,
                'full_name' => $atm->full_name,
                'note' => $atm->note,
                'type' => 'atm',
                'bank' => $atm->bank_name
            ]);
        }
        if($tsr && $tsr->status_bank === 0){
            array_push($listAtmWallet['data'],[
                'account_number' => $tsr->username,
                'link_logo' => $tsr->link_logo,
                'full_name' => $tsr->full_name,
                'note' => $tsr->note,
                'type' => 'thesieure'
            ]);
        }
        return FunResource::responseData(true,Mess::$SUCCESSFULLY,$listAtmWallet,200);
    }
}
