<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\DTO\RoleDTO;
use App\DTO\UserDTO;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{

  protected $roleService;

  public function __construct(RoleService $roleService){
    $this->roleService = $roleService;
  }

  public function resourcesPermissionUserCTLL(Request $request){
    try{
      $groupBy = $request->input('groupBy');
      return $this->roleService->resourcesPermissionUser($groupBy);

    }catch(\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }
 
  public function resourcesPermissionCTLL(Request $request, $roleId){
    try{
      $userDTO = new UserDTO();
      $userDTO->setRoleId($roleId);
      $groupBy = $request->input('groupBy');
      return $this->roleService->resourcesPermission($userDTO, $groupBy);

    }catch(\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function getAllRolesCTLL(Request $request){
    try{
      return $this->roleService->getAllRole();
    }catch(\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function resourcesAllCTLL(Request $request){
    try{
      $roleId = $request->input('roleId');
      $mode = $request->input('mode');
      $roleDTO = new RoleDTO();
      $roleDTO->setRoleId($roleId);
      $roleDTO->setMode($mode);

      return $this->roleService->resourcesAll($roleDTO);
    }catch(\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function permissionsAllCTLL(Request $request){
    try{
      $roleId = $request->input('roleId');
      $resourceId = $request->input('resourceId');
      $mode = $request->input('mode');
      $roleDTO = new RoleDTO();
      $roleDTO->setRoleId($roleId);
      $roleDTO->setMode($mode);
      $roleDTO->setResourceId($resourceId);

      return $this->roleService->permissionsAll($roleDTO);
    }catch(\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  // COMMAND
  public function resourcesCreateCTLL(Request $request){
    try{
      // if (auth()->user()->role->name !== 'Admin') {
      //   return response()->json(['error' => 'Unauthorized'], 403);
      // }

      $key = $request->input('key');
      $name = $request->input('name');
      $iconSolid = $request->input('icon_solid');
      $iconOutlined = $request->input('icon_outlined');
      $path = $request->input('path');

      $roleDTO = new RoleDTO();
      $roleDTO->setKey($key);
      $roleDTO->setName($name);
      $roleDTO->setIconSolid($iconSolid);
      $roleDTO->setIconOutlined($iconOutlined);
      $roleDTO->setPath($path);

      return $this->roleService->resourcesCreate($roleDTO);

    }catch(\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function resourcesUpdateCTLL(Request $request){
    try{
      // if (auth()->user()->role->name !== 'Admin') {
      //   return response()->json(['error' => 'Unauthorized'], 403);
      // }

      $key = $request->input('key');
      $name = $request->input('name');
      $iconSolid = $request->input('icon_solid');
      $iconOutlined = $request->input('icon_outlined');
      $path = $request->input('path');

      $roleDTO = new RoleDTO();
      $roleDTO->setKey($key);
      $roleDTO->setName($name);
      $roleDTO->setIconSolid($iconSolid);
      $roleDTO->setIconOutlined($iconOutlined);
      $roleDTO->setPath($path);

      return $this->roleService->resourcesUpdate($roleDTO);

    }catch(\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function permissionsCreateCTLL(Request $request, $resourceId){
    try{
      // if (auth()->user()->role->name !== 'Admin') {
      //   return response()->json(['error' => 'Unauthorized'], 403);
      // }

      $key = $request->input('key');
      $name = $request->input('name');
      $endpoint = $request->input('endpoint');

      $roleDTO = new RoleDTO();
      $roleDTO->setResourceId($resourceId);
      $roleDTO->setKey($key);
      $roleDTO->setName($name);
      $roleDTO->setEndpoint($endpoint);

      return $this->roleService->permissionsCreate($roleDTO);

    }catch(\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }
  

  public function togglePermissionCTLL(Request $request, $roleId)
  {
    // if (auth()->user()->role->name !== 'Admin') {
    //   return response()->json(['error' => 'Unauthorized'], 403);
    // }

    // $request->validate([
    //   'permission_id' => 'required|exists:permissions,id',
    // ]);

    // $role = Role::findOrFail($roleId);
    // $permissionId = $request->permission_id;

    // $hasPermission = $role->permissions()->where('permission_id', $permissionId)->exists();

    // if ($hasPermission) {
    //   $role->permissions()->detach($permissionId);
    //   return response()->json(['message' => 'Permission disabled'], 200);
    // } else {
    //   $role->permissions()->attach($permissionId);
    //   return response()->json(['message' => 'Permission enabled'], 200);
    // }

    try {
      // if (auth()->user()->role->name !== 'Admin') {
      //   return response()->json(['error' => 'Unauthorized'], 403);
      // }
  
      $request->validate([
        'permission_id' => 'required|exists:permissions,id',
      ]);

      $roleDTO = new RoleDTO();
      $roleDTO->setRoleId($roleId);
      $roleDTO->setPermissionId($request->input('permission_id'));

      return $this->roleService->togglePermission($roleDTO);
    } catch (\Exception $e) {
      // ApiCommon::rollback($e->getMessage());
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }


  public function toggleSubResourceCTLL(Request $request, $roleId)
  {
    // if (auth()->user()->role->name !== 'Admin') {
    //   return response()->json(['error' => 'Unauthorized'], 403);
    // }

    // $request->validate([
    //   'permission_id' => 'required|exists:permissions,id',
    // ]);

    // $role = Role::findOrFail($roleId);
    // $permissionId = $request->permission_id;

    // $hasPermission = $role->permissions()->where('permission_id', $permissionId)->exists();

    // if ($hasPermission) {
    //   $role->permissions()->detach($permissionId);
    //   return response()->json(['message' => 'Permission disabled'], 200);
    // } else {
    //   $role->permissions()->attach($permissionId);
    //   return response()->json(['message' => 'Permission enabled'], 200);
    // }

    try {
      // if (auth()->user()->role->name !== 'Admin') {
      //   return response()->json(['error' => 'Unauthorized'], 403);
      // }
  
      $request->validate([
        'resource_id' => 'required|exists:resources,id',
      ]);

      $roleDTO = new RoleDTO();
      $roleDTO->setRoleId($roleId);
      $roleDTO->setResourceId($request->input('resource_id'));

      return $this->roleService->toggleSubResource($roleDTO);
    } catch (\Exception $e) {
      // ApiCommon::rollback($e->getMessage());
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
