<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SelectQueryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:api'], 'prefix' => '/1'], function ($router) {
    $router->post('Logout', [AuthController::class, 'logout']);
    $router->post('test', [MediaController::class, 'test']);
    $router->group(['prefix' => '/media'], function ($router) {
        $router->post('Upload', [MediaController::class, 'uploadFile']);
        $router->get('GetFile', [MediaController::class, 'getFile']);
    });
    $router->group(['prefix' => '/post'], function ($router) {
        $router->post('/CreatePost', [PostController::class, 'newPostCTLL']);
        $router->get('/', [SelectQueryController::class, 'getPostCTLL']);
        $router->post('/DeletePost', [PostController::class, 'deletePostCTLL']);
        $router->post('/UpdatePost', [PostController::class, 'updatePostCTLL']);
    });
});

Route::group(['middleware' => [], 'prefix' => '/1'], function ($router) {
    $router->post('Register',[AuthController::class,'register']);
    $router->post('Login', [AuthController::class,'login']);
});