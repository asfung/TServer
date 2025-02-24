<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\DTO\RoleDTO;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{

  protected $roleService;

  public function __construct(RoleService $roleService){
    $this->roleService = $roleService;
  }

  public function resourcesCreateCTLL(Request $request){
    try{
      if (auth()->user()->role->name !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 403);
      }

      $key = $request->input('key');
      $name = $request->input('name');

      $roleDTO = new RoleDTO();
      $roleDTO->setKey($key);
      $roleDTO->setName($name);

      return $this->roleService->resourcesCreate($roleDTO);

    }catch(\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function permissionsCreateCTLL(Request $request, $resourceId){
    try{
      if (auth()->user()->role->name !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 403);
      }

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
      if (auth()->user()->role->name !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 403);
      }
  
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
      if (auth()->user()->role->name !== 'Admin') {
        return response()->json(['error' => 'Unauthorized'], 403);
      }
  
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
