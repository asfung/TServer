<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\SelectQueryController;

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

Route::group(['middleware' => ['acl'], 'prefix' => '/1'], function ($router) {
    $router->post('Logout', [AuthController::class, 'logout']);
    $router->post('RefreshToken', [AuthController::class, 'refreshCTLL'])->name('auth.refresh_token');
    $router->post('CheckToken', [AuthController::class, 'checkTokenCTLL'])->name('auth.check_token');
    // $router->post('test', [MediaController::class, 'test']);
    $router->group(['prefix' => '/media'], function ($router) {
        $router->post('Upload', [MediaController::class, 'uploadFile']);
        $router->get('GetFile', [MediaController::class, 'getFile']);
    });

    // Permissions and Role_Permission 
    // $router->group(['prefix' => '/permissions'], function ($router){
    //     $router->group(['prefix' => '/roles'], function ($router) {
    //         $router->post('/{role}/permission/toggle', [RoleController::class, 'togglePermissionCTLL'])->name('resource.access.toggle');
    //     });
    // });

    // // Resources and Role_Resource
    // $router->group(['prefix' => '/resources'], function ($router){
    //     $router->group(['prefix' => '/roles'], function ($router) {
    //         $router->post('/resource/toggle', [RoleController::class, ''])->name('subresource.create');
    //     });
    //     $router->post('/Create', [RoleController::class, ''])->name('resources.create');
    //     $router->post('/Delete', [RoleController::class, ''])->name('resource.delete');
    // });

    // RESOURCES
    $router->group(['prefix' => '/resources'], function($router) {
        $router->post('Create', [RoleController::class, 'resourcesCreateCTLL'])->name('resources.create');
        $router->post('All', [RoleController::class, 'resourcesAllCTLL'])->name('resources.all');

        // SUB-ROLES
        $router->group(['prefix' => '/roles'], function($router) {
            // ROLE_RESOURCE
            $router->group(['prefix' => '/resource'], function($router) {
                $router->post('/{roleId}/Toggle', [RoleController::class, 'toggleSubResourceCTLL'])->name('resource.role_resource.toggle');

            });
        });
    });

    // PERMISSIONS
    $router->group(['prefix' => '/permissions'], function($router) {
        $router->post('{resourceId}/Create', [RoleController::class, 'permissionsCreateCTLL'])->name('permissions.create');
        $router->post('User', [RoleController::class, 'resourcesPermissionUserCTLL'])->name('permissions.user');
        $router->post('User/{roleId}', [RoleController::class, 'resourcesPermissionCTLL'])->name('permissions.resource-permission');
        $router->post('All', [RoleController::class, 'permissionsAllCTLL'])->name('permissions.all');
 
        // SUB-ROLES
        $router->group(['prefix' => '/roles'], function($router) {
            // ROLE_PERMISSION
            $router->group(['prefix' => '/permission'], function($router) {
                $router->post('/{roleId}/Toggle', [RoleController::class, 'togglePermissionCTLL'])->name('resource.role_permission.toggle');
            });
        });
    });

    // ROLE
    $router->group(['prefix' => '/role'], function ($router) {
        $router->post('/All', [RoleController::class, 'getAllRolesCTLL'])->name('roles.all');
    });

    $router->group(['prefix' => '/post'], function ($router) {
        $router->post('/CreatePost', [PostController::class, 'newPostCTLL'])->name('post.create');
        $router->get('/', [SelectQueryController::class, 'getPostCTLL']);
        $router->post('/DeletePost', [PostController::class, 'deletePostCTLL']);
        $router->post('/UpdatePost', [PostController::class, 'updatePostCTLL']);

        // FOLLOW
        $router->group(['prefix' => '/follow'], function ($router) {
            $router->post('/CreateDelete', [FollowController::class, 'createCTLL']);
        });

        // LIKE
        $router->group(['prefix' => '/like'], function ($router) {
            $router->post('/Like', [LikeController::class, 'likeToCTLL']);
        });

        // BOOKMARK
        $router->group(['prefix' => '/bookmark'], function ($router) {
            $router->post('/CreateDelete', [BookmarkController::class, 'storeBookmarkCTLL']);
        });

        // MEDIA
        $router->group(['prefix' => '/media'], function ($router) {
            $router->post('/MediaPostIdEdit', [MediaController::class, 'mediaPostIdEditCTLL']);
            $router->post('/MediaDelete', [MediaController::class, 'mediaPostIdDeleteCTLL']);
        });

    });
});

Route::group(['middleware' => [], 'prefix' => '/1'], function ($router) {
    $router->post('Register',[AuthController::class,'registerCTLL']);
    $router->post('Login', [AuthController::class,'loginCTLL']);
});
