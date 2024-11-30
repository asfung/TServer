<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];


    public function post(){
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

}
