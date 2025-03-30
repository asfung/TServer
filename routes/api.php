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
use App\Http\Controllers\RepostController;
use App\Http\Controllers\SelectQueryController;
use App\Services\SelectQueryService;

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
    $router->post('RefreshToken', [AuthController::class, 'refreshCTLL'])->name('auth.token.refresh');
    $router->post('CheckToken', [AuthController::class, 'checkTokenCTLL'])->name('auth.token.check');
    // $router->post('test', [MediaController::class, 'test']);

    // USER
    $router->group(['prefix' => '/user'], function ($router) {
        $router->get('/{username}', [SelectQueryController::class, 'getUsernameCTLL'])->name('user.username.get');
    });

    // MEDIA
    $router->group(['prefix' => '/media'], function ($router) {
        $router->post('Upload', [MediaController::class, 'uploadFile'])->name('media.upload');
        $router->get('GetFile', [MediaController::class, 'getFile'])->name('media.read');
    });

    // RESOURCES
    $router->group(['prefix' => '/resources'], function($router) {
        $router->post('Create', [RoleController::class, 'resourcesCreateCTLL'])->name('resources.create');
        $router->post('Update', [RoleController::class, 'resourcesUpdateCTLL'])->name('resources.update');
        $router->post('All', [RoleController::class, 'resourcesAllCTLL'])->name('resources.all');
        $router->post('Delete', [RoleController::class, 'resourcesDeleteCTLL'])->name('resources.delete');

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
        $router->post('All', [RoleController::class, 'permissionsAllCTLL'])->name('permissions.all');
        $router->post('Update', [RoleController::class, 'permissionsUpdateCTLL'])->name('permissions.update');
        $router->post('Delete', [RoleController::class, 'permissionsDeleteCTLL'])->name('permissions.delete');

        $router->post('User', [RoleController::class, 'resourcesPermissionUserCTLL'])->name('permissions.user');
        $router->post('User/{roleId}', [RoleController::class, 'resourcesPermissionCTLL'])->name('permissions.user.role');
 
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

    // FOLLOW
    $router->group(['prefix' => '/follow'], function ($router) {
        $router->post('/Create', [FollowController::class, 'createCTLL']);
        $router->post('/Delete', [FollowController::class, 'deleteCTLL']);
        $router->post('/FollowToggle', [FollowController::class, 'toggleFollowCTLL'])->name('follow.toggle');
    });

    // POST
    $router->group(['prefix' => '/post'], function ($router) {
        $router->post('/CreatePost', [PostController::class, 'newPostCTLL'])->name('post.create');
        $router->get('/', [SelectQueryController::class, 'getPostCTLL'])->name('post.get');
        $router->get('/Replies', [SelectQueryController::class, 'getPostReplyCTLL'])->name('post.replies.get');
        $router->post('/DeletePost', [PostController::class, 'deletePostCTLL']);
        $router->post('/UpdatePost', [PostController::class, 'updatePostCTLL'])->name('post.delete');

        // REPOST
        $router->group(['prefix' => '/repost'], function ($router) {
            $router->post('/RepostToggle', [RepostController::class, 'repostToggleCTLL'])->name('post.repost.toggle');
        });

        // LIKE
        $router->group(['prefix' => '/like'], function ($router) {
            $router->post('/Like', [LikeController::class, 'likeToCTLL'])->name('post.like.toggle');
        });

        // BOOKMARK
        $router->group(['prefix' => '/bookmark'], function ($router) {
            $router->post('/ToggleBookmark', [BookmarkController::class, 'storeBookmarkCTLL'])->name('post.bookmark.toggle');
        });

        // MEDIA
        $router->group(['prefix' => '/media'], function ($router) {
            $router->post('/MediaPostIdEdit', [MediaController::class, 'mediaPostIdEditCTLL'])->name('media.edit_media_post_id');
            $router->post('/MediaDelete', [MediaController::class, 'mediaPostIdDeleteCTLL'])->name('media.delete');
        });

    });
});

Route::group(['middleware' => [], 'prefix' => '/1'], function ($router) {
    $router->post('Register',[AuthController::class,'registerCTLL']);
    $router->post('Login', [AuthController::class,'loginCTLL']);
});
