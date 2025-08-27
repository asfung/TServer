<?php

use App\Common\ApiCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\GmailController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RepostController;
use App\Http\Controllers\SelectQueryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtilController;
use Embed\Embed;

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
    $router->post('Logout', [AuthController::class, 'logoutCTLL'])->name('auth.logout');
    $router->post('RefreshToken', [AuthController::class, 'refreshCTLL'])->name('auth.token.refresh');
    $router->post('CheckToken', [AuthController::class, 'checkTokenCTLL'])->name('auth.token.check');
    $router->post('ChangePassword', [AuthController::class, 'changePasswordCTLL'])->name('auth.password.change');
    // $router->post('test', [MediaController::class, 'test']);

    // USER
    $router->group(['prefix' => '/user'], function ($router) {
        $router->get('/{username}', [SelectQueryController::class, 'getUsernameCTLL'])->name('user.username.get');
        $router->get('/', [SelectQueryController::class, 'searchUserCTLL'])->name('user.search');
        $router->post('/Update', [UserController::class, 'updateUserCTLL'])->name('user.update');

        // for ADMIN role
        $router->group(['prefix' => '/admin'], function ($router) {
            $router->post('/', [UserController::class, 'index'])->name('user.admin.index');
            $router->post('/Show/{id}', [UserController::class, 'show'])->name('user.admin.show');
            $router->post('/Create', [UserController::class, 'store'])->name('user.admin.store');
            $router->post('/Update/{id}', [UserController::class, 'update'])->name('user.admin.update');
            $router->post('/Delete/{id}', [UserController::class, 'destroy'])->name('user.admin.destroy');
        });
    });

    // EMAIL
    $router->group(['prefix' => '/email'], function($router) {
        $router->get('/Send', [GmailController::class, 'send'])->name('email.send');
    });

    // MEDIA
    $router->group(['prefix' => '/media'], function ($router) {
        $router->post('Upload', [MediaController::class, 'uploadFile'])->name('media.upload');
        $router->get('GetFile', [MediaController::class, 'getFile'])->name('media.read');
    });

    // NOTIFICATION
    $router->group(['prefix' => '/notifications'], function ($router) {
        $router->get('All', [NotificationController::class, 'getAllCTLL'])->name('notifications.all');
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
        $router->post('/DeletePost', [PostController::class, 'deletePostCTLL'])->name('post.delete');
        $router->post('/UpdatePost', [PostController::class, 'updatePostCTLL'])->name('post.update');

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

    // TAGS
    $router->group(['prefix' => '/tags'], function ($router) {
        $router->get('/', [SelectQueryController::class, 'getMostTagCTLL'])->name('tag.get');
    });

    // $router->get("/LinkPreview", function(Request $request) {
    //     $url = $request->input('url');
    //     if (!$url) return ApiCommon::sendResponse(null, 'must provide the \'url\'', 400, false);
    //
    //     try {
    //         $embed = new Embed();
    //         $info = $embed->get($url);
    //
    //         return ApiCommon::sendResponse([
    //             'title' => $info->title ?? '',
    //             'description' => $info->description ?? '',
    //             'image' => $info->image ?? null,
    //             'url' => $url,
    //         ], 'berhasil dapat link preview', 200, true);
    //
    //     }catch(\Exception $e){
    //         return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    //     }
    // })->name('link.preview');
    $router->get('LinkPreview', [UtilController::class, 'getLinkPreview'])->name('link.preview');

});

Route::group(['middleware' => [], 'prefix' => '/1'], function ($router) {
    $router->post('Register',[AuthController::class,'registerCTLL']);
    $router->post('Login', [AuthController::class,'loginCTLL']);
});
