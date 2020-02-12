<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Permissions\HasPermissionsTrait;

class User extends Authenticatable
{
    use Notifiable, HasPermissionsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'ptoken'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

	public function roles()
	{
		return $this->belongsToMany('App\Role', 'users_roles');
	}
    public function avatar()
	{
        return asset('storage/' . $this->avatar ?: 'storage/avatars/default.png');
    }
    // users that are followed by this user
    public function following() {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    // users that follow this user
    public function followers() {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

	public function photos()
	{
		return $this->hasMany('App\Photo');
	}
	public function sets()
	{
		return $this->hasMany('App\Set');
	}
	public function setPhotos()
	{
		return $this->hasMany('App\SetPhoto');
	}
	public function videos()
	{
		return $this->hasMany('App\Video');
	}
	public function comments()
	{
		return $this->hasMany('App\Comment');
	}
}
