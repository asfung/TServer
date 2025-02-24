<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model{

    protected $guarded = [];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}

