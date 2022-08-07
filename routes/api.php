<?php

use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\SecurityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
    //---------------------------------------AUTHENTICATION------------------------------------------
    
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

        //-----------------------------------ROUTE CATEGORIES------------------------------------------------
        
        //----------------------------------------END--------------------------------------------------------
    });
    


    //---------------------------------------AUTHORIZED ADMIN----------------------------------------------
    
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
            //Route::post('/delete/{id}',[UserController::class,"destroy"])->name("userDestroy");
            Route::put('/update/{id}',[UserController::class,"update"])->name("updateUser");
        });
        //-----------------------------------------END--------------------------------------------------------

        //--------------------------------------CATEGORIES-----------------------------------------------
        Route::group([
            "prefix"=>"categories"
        ],
        function(){
            Route::post('/create',[CategoryController::class,"create"])->name("createCategory");
            Route::get('/show/{id}',[CategoryController::class,"show"])->name("showCategory");
            Route::delete('/destroy/{id}',[CategoryController::class,"destroy"])->name("destroyCategory");
            Route::put('/update/{id}',[CategoryController::class,"update"])->name("updateCategory");
        });
        //------------------------------------------END------------------------------------------------------
    });

    //----------------------------------------ROUTE PUBLIC----------------------------------------------------

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

        //---------------------------------------CATEGORIES-----------------------------------------------
        Route::group([
            "prefix"=>"categories"
        ],
        function(){
            Route::get('/index',[CategoryController::class,"index"])->name("indexCategory");
        });
        //------------------------------------------END--------------------------------------------------------
    });


Route::get('/unauthorized',[SecurityController::class,"unauthorized"])->name("unauthorized");
Route::get('/token-Not-Exist',[SecurityController::class,"tokenNotExist"])->name("tokenNotExist");
Route::get('/request-failed',[SecurityController::class,'securityRequest'])->name('securityRequest');



