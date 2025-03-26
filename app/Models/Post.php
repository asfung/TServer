<?php

namespace App\Models;

use CalebDW\Laraflake\Concerns\HasSnowflakes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasSnowflakes, HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent()
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }
    public function replies()
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    public function likes(){
        return $this->hasMany(Like::class, 'post_id', 'id');
    }
    public function likedBy(User $user)
    {
        return $this->likes->contains('user_id', $user->id);
    }
    public function getLikeCount(){
        return $this->likes()->count();
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'post_id', 'id');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'post_id', 'id');
    }
    public function isBookmarked(User $user)
    {
        return $this->bookmarks->contains('user_id', $user->id);
    }
    public function getBookmarksByUserId($userId)
    {
        return $this->bookmarks()->where('user_id', $userId)->get();
    }

    public function reposts()
    {
        return $this->hasMany(Repost::class, 'post_id', 'id');
    }
    public function reposted(User $user)
    {
        return $this->reposts->contains('user_id', $user->id);
    }
    public function getRepostCount(){
        return $this->reposts()->count();
    }
    public function getRepostsByUserId($userId)
    {
        return $this->reposts()->where('user_id', $userId)->get();
    }

}
