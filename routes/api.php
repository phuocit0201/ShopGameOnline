<?php

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
    //route user
    Route::group([
        "middleware"=>"auth_login",
        "prefix"=>"v1/users"
    ],
    function(){
        Route::get('/get-me',[UserController::class,"getMe"])->name("getMe");
        Route::post('/logout',[UserController::class,"logout"])->name("logout");
        Route::put('/change-password',[UserController::class,"changePassword"])->name("changePassword");
    
    });
    
    //route admin
    
    Route::group([
        "middleware"=>"auth_admin",
        "prefix"=>"v1/users"
    ],
    function(){
        Route::get('/index',[UserController::class,"index"])->name("userIndex");
        Route::get('/show/{id}',[UserController::class,"show"])->name("userShow");
        Route::delete('/delete/{id}',[UserController::class,"destroy"])->name("userDestroy");
        Route::put('/update/{id}',[UserController::class,"update"])->name("updateUser");
    });
    
//route public
Route::get('/unauthorized',[SecurityController::class,"unauthorized"])->name("unauthorized");
Route::get('/token-Not-Exist',[SecurityController::class,"tokenNotExist"])->name("tokenNotExist");

Route::get('/get-categorys',[CategoryController::class,"index"])->name("getCategory");
Route::get('/get-category/{id}',[CategoryController::class,"show"])->name("showCategory");

Route::group([
    "middleware"=>"api",
    "prefix"=>"v1/users",
],
function(){
    Route::post('/login',[UserController::class,"login"])->name("login");
    Route::post('/create',[UserController::class,"create"])->name("createUser");
});

Route::get('/request-failed',[SecurityController::class,'securityRequest'])->name('securityRequest');
