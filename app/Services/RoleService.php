<?php

namespace App\Services;

use App\Common\ApiCommon;
use App\DTO\RoleDTO;
use App\DTO\UserDTO;
use App\Models\Permission;
use App\Models\Resource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class RoleService
{

  // QUERY
  public function resourcesPermissionUser($groupBy = null){
    try{
      $userId = ApiCommon::getUserId();

      $user = User::find($userId);
      $role = Role::with('resources.permissions')->find($user->role->id);

      $userResources = Role::find($user->role->id)->resources->pluck('key');
      $userPermissions = Role::find($user->role->id)->permissions->pluck('key');

      $data = $role->resources->map(function ($resource) use ($role) {
        $rolePermissions = $role->permissions->pluck('id');

        return [
          'id' => $resource->id,
          'key' => $resource->key,
          'name' => $resource->name,
          'icon_solid' => $resource->icon_solid,
          'icon_outlined' => $resource->icon_outlined,
          'resource_permissions' => $resource->permissions
            ->filter(function ($permission) use ($rolePermissions) {
              return $rolePermissions->contains($permission->id);
            })
            ->map(function ($permission) {
              return [
                'id' => $permission->id,
                'key' => $permission->key,
                'name' => $permission->name,
                'endpoint_name' => $permission->endpoint,
                'resource_id' => $permission->resource_id,
                'endpoint_url' => Route::getRoutes()->getByName($permission->endpoint)->uri
              ];
            })
            ->values()
            ->toArray(),
        ];
      });
    
      $response = null;
      if(!$groupBy){
        $response = $data;
      }else{
        if($groupBy == 'key'){
          $formatted = [
            'role' => $user->role->name,
            'resources' => $userResources,
            'permissions' => $userPermissions
          ];
          $response = $formatted;
        }else{
          return ApiCommon::sendResponse(null, $groupBy . ' not exists', 400, false);
        }
      }

      return ApiCommon::sendResponse($response, 'Data Resource Permission User', 200);

    }catch(\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function resourcesPermission(UserDTO $userDTO, $groupBy = null){
    try{
      $roleId = $userDTO->getRoleId();
      $userResources = Role::find($roleId)->resources->pluck('key');
      $userPermissions = Role::find($roleId)->permissions->pluck('key');

      $role = Role::with('resources.permissions')->find($roleId);


      $data = $role->resources->map(function ($resource) use ($role) {
        $rolePermissions = $role->permissions->pluck('id'); 

      return [
        'id' => $resource->id,
        'key' => $resource->key,
        'name' => $resource->name,
        'icon_solid' => $resource->icon_solid,
        'icon_outlined' => $resource->icon_outlined,
        'resource_permissions' => $resource->permissions
          ->filter(function ($permission) use ($rolePermissions) {
              return $rolePermissions->contains($permission->id);
          })
          ->map(function ($permission) {
            return [
              'id' => $permission->id,
              'key' => $permission->key,
              'name' => $permission->name, 
              'endpoint_name' => $permission->endpoint,
              'resource_id' => $permission->resource_id,
              'endpoint_url' => Route::getRoutes()->getByName($permission->endpoint)->uri
            ];
          })
          ->values() 
          ->toArray(),
        ];
      });

      $response = null;
      if(!$groupBy){
        $response = $data;
      }else{
        if($groupBy === 'key'){
          $formatted = [
            'resources' => $userResources,
            'permissions' => $userPermissions
          ];
          $response = $formatted;
        }else{
          return ApiCommon::sendResponse(null, $groupBy . ' not exists', 400);
        }
      }

      return ApiCommon::sendResponse($response, 'Data Resource Permission', 200);

    }catch(\Exception $e){
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function getAllRole(){
    try {

      $roles = Role::all();
      return ApiCommon::sendResponse($roles, 'Data Roles');

    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function resourcesAll(RoleDTO $roleDTO){
    try {
      $roleId = $roleDTO->getRoleId();
      $mode = $roleDTO->getMode() ?? 'all';

      $query = Resource::select('resources.*');

      switch ($mode) {
        case 'all':
          $query->leftJoin('role_resource', function ($join) use ($roleId) {
            $join->on('resources.id', '=', 'role_resource.resource_id')
              ->where('role_resource.role_id', '=', $roleId);
          })
            ->selectRaw('CASE WHEN role_resource.resource_id IS NOT NULL THEN 1 ELSE 0 END as isExists');
          break;

        case 'available':
          $query->join('role_resource', function ($join) use ($roleId) {
            $join->on('resources.id', '=', 'role_resource.resource_id')
              ->where('role_resource.role_id', '=', $roleId);
          })
            ->selectRaw('1 as isExists');
          break;

        case 'not_available':
          $query->leftJoin('role_resource', function ($join) use ($roleId) {
            $join->on('resources.id', '=', 'role_resource.resource_id')
              ->where('role_resource.role_id', '=', $roleId);
          })
            ->whereNull('role_resource.resource_id')
            ->selectRaw('0 as isExists');
          break;

        default:
          return response()->json(['error' => 'Invalid mode specified'], 400);
      }

      // $resource = $query->get()->map(function ($item) {
      //   $item->isExists = (bool) $item->isExists;
      //   return $item;
      // });
      $resource = $query->get();

      return ApiCommon::sendResponse($resource, 'Data Resource');

    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function permissionsAll(RoleDTO $roleDTO){
    try {
      $roleId = $roleDTO->getRoleId();
      $mode = $roleDTO->getMode() ?? 'all';
      $resourceId = $roleDTO->getResourceId();

      $query = Permission::select('permissions.*');

      if($resourceId && $resourceId !== null){
        $query->where('permissions.resource_id', $resourceId);
      }
      switch ($mode) {
        case 'all':
          $query->leftJoin('role_permission', function ($join) use ($roleId, $resourceId) {
            $join->on('permissions.id', '=', 'role_permission.permission_id') 
              ->where('role_permission.role_id', '=', $roleId);
          })
            ->selectRaw('CASE WHEN role_permission.permission_id IS NOT NULL THEN 1 ELSE 0 END as isExists');
          break;

        case 'available':
          $query->join('role_permission', function ($join) use ($roleId, $resourceId) {
            $join->on('permissions.id', '=', 'role_permission.permission_id')
              ->where('role_permission.role_id', '=', $roleId);
          })
            ->selectRaw('1 as isExists');
          break;

        case 'not_available':
          $query->leftJoin('role_permission', function ($join) use ($roleId, $resourceId) {
            $join->on('permissions.id', '=', 'role_permission.permission_id')
              ->where('role_permission.role_id', '=', $roleId);
          })
            ->whereNull('role_permission.permission_id')
            ->selectRaw('0 as isExists');
          break;

        default:
          return response()->json(['error' => 'Invalid mode specified'], 400);
      }

      // Route::getRoutes()->getByName($permission->endpoint)->uri
      // $permission = $query->get()->map(function ($item) {
      //   $item->isExists = (bool) $item->isExists; 
      //   return $item;
      // });
      // $permission = $query->get();

      $permissions = $query->get()->map(function ($item) {
        $item->uri = Route::getRoutes()->getByName($item->endpoint)->uri ?? 'N/A';
        $item->methods = Route::getRoutes()->getByName($item->endpoint)->methods ?? 'N/A';
        return $item;
    });
      return ApiCommon::sendResponse($permissions, 'Data Permission');
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  // COMMAND 
  public function resourcesCreate(RoleDTO $roleDTO){
    try{
      DB::beginTransaction();
      $key = $roleDTO->getKey();
      $name = $roleDTO->getName();
      $iconSolid = $roleDTO->getIconSolid();
      $iconOutlined = $roleDTO->getIconOutlined();
      $path = $roleDTO->getPath();

      $hasResource = Resource::where('key', $key)->exists();
      if($hasResource){
        return ApiCommon::sendResponse(null, 'Resource has already exist', 403, false);
      }
      
      DB::commit();
      $newResource = new Resource();
      $newResource->key = $key;
      $newResource->name = $name;
      $newResource->icon_solid = $iconSolid;
      $newResource->icon_outlined = $iconOutlined;
      $newResource->path = $path;
      $newResource->save();

      return ApiCommon::sendResponse($newResource, 'Resource Created', 201);

    }catch(\Exception $e){
      DB::rollBack();
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function resourcesUpdate(RoleDTO $roleDTO){
    try {
      DB::beginTransaction();

      $key = $roleDTO->getKey();

      $resource = Resource::where('key', $key)->first();
      if (!$resource) {
        return ApiCommon::sendResponse(null, 'resource not exists', 404, false);
      }

      $resource->name = $roleDTO->getName() ?? $resource->name;
      $resource->icon_solid = $roleDTO->getIconSolid() ?? $resource->icon_solid;
      $resource->icon_outlined = $roleDTO->getIconOutlined() ?? $resource->icon_outlined;
      $resource->path = $roleDTO->getPath() ?? $resource->path;
      $resource->save();

      DB::commit();

      return ApiCommon::sendResponse($resource->fresh(), 'resource updated', 200);
    } catch (\Exception $e) {
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
