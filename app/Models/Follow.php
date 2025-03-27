<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];

    // from user
    public function follower(){
        return $this->belongsTo(User::class, 'user_id_follower', 'id');
    }
    // to user follow 
    public function followed(){
        return $this->belongsTo(User::class, 'user_id_followed', 'id');
    }

}
