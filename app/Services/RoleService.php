<?php

namespace App\Services;

use App\Common\ApiCommon;
use App\DTO\RoleDTO;
use App\Models\Permission;
use App\Models\Resource;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleService
{

  public function resourcesCreate(RoleDTO $roleDTO){
    try{
      DB::beginTransaction();
      $key = $roleDTO->getKey();
      $name = $roleDTO->getName();

      $hasResource = Resource::where('key', $key)->exists();
      if($hasResource){
        return ApiCommon::sendResponse(null, 'Resource has already exist', 403, false);
      }
      
      DB::commit();
      $newResource = new Resource();
      $newResource->key = $key;
      $newResource->name = $name;
      $newResource->save();

      return ApiCommon::sendResponse($newResource, 'Resource Created', 201);

    }catch(\Exception $e){
      DB::rollBack();
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function permissionsCreate(RoleDTO $roleDTO){

    try{
      DB::beginTransaction();
      $key = $roleDTO->getKey();
      $name = $roleDTO->getName();
      $endpoint = $roleDTO->getEndpoint();

      $resourceId = $roleDTO->getResourceId();

      $hasPermission = Permission::where('key', $key)->exists();

      if($hasPermission){
        return ApiCommon::sendResponse(null, 'Permission has already exist', 403, false);
      }
      
      DB::commit();
      $newPermission = new Permission();
      $newPermission->key = $key;
      $newPermission->name = $name;
      $newPermission->endpoint = $endpoint;
      $newPermission->resource_id = $resourceId;
      $newPermission->save();

      return ApiCommon::sendResponse($newPermission, 'Permission Created', 201);

    }catch(\Exception $e){
      DB::rollBack();
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function togglePermission(RoleDTO $roleDTO)
  {
    try {
      $roleId = $roleDTO->getRoleId();

      $role = Role::findOrFail($roleId);
      $permissionId = $roleDTO->getPermissionId();

      $hasPermission = $role->permissions()->where('permission_id', $permissionId)->exists();
      $permission = Permission::findOrFail($permissionId);

      $response = [
        'role_id' => $role->id,
        'role_name' => $role->name,
        'permission' => $permission,
      ];

      if ($hasPermission) {
        $role->permissions()->detach($permissionId);
        // return response()->json(['message' => 'Permission disabled'], 200);
        return ApiCommon::sendResponse($response, 'Permission Disabled', 200);
      } else {
        $role->permissions()->attach($permissionId);
        // return response()->json(['message' => 'Permission enabled'], 200);
        return ApiCommon::sendResponse($response, 'Permission Enabled', 200);
      }
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function toggleSubResource(RoleDTO $roleDTO)
  {
    try {
      $roleId = $roleDTO->getRoleId();

      $role = Role::findOrFail($roleId);
      $resourceId = $roleDTO->getResourceId();

      $hasResource = $role->resources()->where('resource_id', $resourceId)->exists();
      $resource = Resource::findOrFail($resourceId);

      $response = [
        'role_id' => $role->id,
        'role_name' => $role->name,
        'resource' => $resource,
      ];

      if ($hasResource) {
        $role->resources()->detach($resourceId);
        // return response()->json(['message' => 'Permission disabled'], 200);
        return ApiCommon::sendResponse($response, 'Resource Disabled', 200);
      } else {
        $role->resources()->attach($resourceId);
        // return response()->json(['message' => 'Permission enabled'], 200);
        return ApiCommon::sendResponse($response, 'Resource Enabled', 200);
      }
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

}
