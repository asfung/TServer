<?php

namespace App\Models;

use CalebDW\Laraflake\Concerns\HasSnowflakes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasSnowflakes, HasFactory;
    protected $guarded = [];

    public function likes(){
        return $this->hasMany(Like::class, 'post_id', 'id');
    }

    public function getLikeCount(){
        return $this->likes()->count();
    }
}
