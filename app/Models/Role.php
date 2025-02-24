<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model{

    protected $guarded = [];

    public function resources()
    {
        return $this->belongsToMany(Resource::class, 'role_resource');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

}