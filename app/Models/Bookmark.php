<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function getLikeCount(){
        return $this->likes()->count();
    }
}
