<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Common\ApiCommon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
  use HasApiTokens, HasFactory, Notifiable, HasUuids;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  // protected $fillable = [
  //     'name',
  //     'email',
  //     'password',
  // ];
  protected $guarded = [];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [
      'user_id' => $this->id,
      'email' => $this->email,
      'display_name' => $this->display_name,
      'username' => $this->username,
      // 'profile_image'=>$this->username,
    ];
  }

  protected static function boot(){
    parent::boot();

    static::saving(function ($user) {
      if (preg_match('/\s/', $user->username)) {
        throw new \Exception("Username cannot contain spaces.");
      }
    });
  }

  public function getPostCount(){
    return $this->hasMany(Post::class, 'user_id', 'id')->whereNull('deleted_at')->count();
  }

  public function role()
  {
    return $this->belongsTo(Role::class);
  }

  public function media()
  {
    return $this->belongsTo(Media::class, 'profile_image')->whereNull('deleted_at');
  }

  public function followers()
  {
    return $this->hasMany(Follow::class, 'user_id_followed', 'id');
  }

  public function following()
  {
    return $this->hasMany(Follow::class, 'user_id_follower', 'id')->whereNull('deleted_at');
  }

  public function getFollowersCountAttribute()
  {
    return $this->followers()->whereNull('deleted_at')->count();
  }

  public function getFollowingCountAttribute()
  {
    return $this->following()->whereNull('deleted_at')->count();
  }

  public function getIsFollowedAttribute(): bool
  {
    if (!auth()->check()) {
      return false; 
    }
    $authUserId = auth()->id();
    // return $this->followers()->where('user_id_follower', $authUserId)->exists();
    return $this->followers()->where('user_id_follower', $authUserId)->whereNull('deleted_at')->exists();
  }

  public function quotes()
  {
    return $this->hasMany(Quote::class, 'user_id');
  }

  // TODO: make it perpage 
  public function interactions()
  {
    $likedPostIds = $this->hasManyThrough(Post::class, Like::class, 'user_id', 'id', 'id', 'post_id')->pluck('posts.id');
    $repliedPostIds = $this->hasMany(Post::class, 'user_id')->whereNotNull('parent_id')->where('user_id', '!=', ApiCommon::getUserId())->pluck('id');
    $repostedPostIds = $this->hasManyThrough(Post::class, Repost::class, 'user_id', 'id', 'id', 'post_id')->pluck('posts.id');
    $followingPostIds = Post::whereIn('user_id', $this->following()->pluck('user_id_followed'))->pluck('id');
    $quoteIds = $this->hasMany(Quote::class, 'user_id')->pluck('id');

    return $likedPostIds
      ->merge($repliedPostIds)
      ->merge($repostedPostIds)
      ->merge($followingPostIds)
      ->merge($quoteIds)
      ->unique()
      ->values()
      ->map(fn($id) => (string) $id);
  }
}
