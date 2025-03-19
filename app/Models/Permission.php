<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model{

    protected $guarded = [];
    
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    protected $casts = [
        'isExists' => 'boolean',
    ];
}