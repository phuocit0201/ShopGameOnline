<?php

use App\Http\Controllers\api\v1\AccountController;
use App\Http\Controllers\api\v1\CardController;
use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\OrderController;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\SecurityController;
use Illuminate\Support\Facades\Route;

//-------------------------------------------AUTHENTICATION------------------------------------------
    
    Route::group([
        "middleware"=>"auth_login",
        "prefix"=>"v1"
    ],function(){
        //-----------------------------------ROUTE USERS---------------------------------------------------
        Route::group([
            "prefix"=>"users"
        ],
        function(){
            Route::get('/get-me',[UserController::class,"getMe"])->name("getMe");
            Route::post('/logout',[UserController::class,"logout"])->name("logout");
            Route::put('/change-password',[UserController::class,"changePassword"])->name("changePassword");
        });
        //---------------------------------------END---------------------------------------------------------

        //-----------------------------------ROUTE ORRDERS------------------------------------------------
        Route::group([
            "prefix"=>"orders"
        ],
        function(){
            Route::post('/create',[OrderController::class,"store"])->name("createOrder");
            Route::get('/order-detail/{id}',[OrderController::class,"orderDetail"])->name("orderDetail");
        });
        //----------------------------------------END--------------------------------------------------------

        //-----------------------------------ROUTE CARDS------------------------------------------------
        Route::group([
            "prefix"=>"cards"
        ],
        function(){
            Route::get('/history',[CardController::class,"getHistoryByUser"])->name("getHistoryCardByUser");
            //Route::get('/order-detail/{id}',[OrderController::class,"orderDetail"])->name("orderDetail");
            // Route::post('/logout',[UserController::class,"logout"])->name("logout");
            // Route::put('/change-password',[UserController::class,"changePassword"])->name("changePassword");
        });
        //----------------------------------------END--------------------------------------------------------
    });
    


//--------------------------------------------AUTHORIZED ADMIN----------------------------------------------
    
    Route::group([
        "middleware"=>"auth_admin",
        "prefix"=>"v1"
    ],function(){
        //-----------------------------------------USERS----------------------------------------------------
        Route::group([
            "prefix"=>"users"
        ],
        function(){
            Route::get('/index',[UserController::class,"index"])->name("userIndex");
            Route::get('/show/{id}',[UserController::class,"show"])->name("userShow");
            Route::post('/update-money',[UserController::class,"updateMoney"])->name("userUpdateMoney");
            Route::put('/update/{id}',[UserController::class,"update"])->name("updateUser");
        });
        //-----------------------------------------END--------------------------------------------------------

        //--------------------------------------CATEGORIES----------------------------------------------------
        Route::group([
            "prefix"=>"categories"
        ],
        function(){
            Route::post('/create',[CategoryController::class,"create"])->name("createCategory");
            Route::get('/show/{id}',[CategoryController::class,"show"])->name("showCategory");
            Route::delete('/destroy/{id}',[CategoryController::class,"destroy"])->name("destroyCategory");
            Route::put('/update/{id}',[CategoryController::class,"update"])->name("updateCategory");
        });
        //----------------------------------------END------------------------------------------------------

        //--------------------------------------ACCOUNT----------------------------------------------------
        Route::group([
            "prefix"=>"accounts"
        ],
        function(){
            Route::post('/create',[AccountController::class,"store"])->name("createAccount");
            Route::delete('/destroy/{id}',[AccountController::class,"destroy"])->name("destroyAccount");
            Route::put('/update/{id}',[AccountController::class,"update"])->name("updateAcount");
        });
        //----------------------------------------END------------------------------------------------------
    });

//---------------------------------------------ROUTE PUBLIC----------------------------------------------------

    Route::group([
        "prefix"=>"v1"
    ],function(){
        //----------------------------------------USERS----------------------------------------------------
        Route::group([
            "prefix"=>"users"
        ],
        function(){
            Route::post('/login',[UserController::class,"login"])->name("login");
            Route::post('/create',[UserController::class,"create"])->name("createUser");
        });
        //------------------------------------------END--------------------------------------------------------

        //---------------------------------------CATEGORIES----------------------------------------------------
        Route::group([
            "prefix"=>"categories"
        ],
        function(){
            Route::get('/index',[CategoryController::class,"index"])->name("indexCategory");
        });
        //------------------------------------------END--------------------------------------------------------
        
        //---------------------------------------ACCOUNTS----------------------------------------------------
        Route::group([
            "prefix"=>"accounts"
        ],
        function(){
            Route::get('/index',[AccountController::class,"index"])->name("indexAcounts");
            Route::get('/account-by-category/{id}',[AccountController::class,"showAccountByCategory"])->name("showAccountByCategory");
            Route::get('/show/{id}',[AccountController::class,"show"])->name("showAccount");
        });
        //------------------------------------------END--------------------------------------------------------
    });


Route::get('/unauthorized',[SecurityController::class,"unauthorized"])->name("unauthorized");
Route::get('/token-Not-Exist',[SecurityController::class,"tokenNotExist"])->name("tokenNotExist");
Route::get('/request-failed',[SecurityController::class,'securityRequest'])->name('securityRequest');



