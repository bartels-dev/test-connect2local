<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function likes()
	{
		return$this->morphMany('App\Like', 'entity');
	}
	
	public function comments()
	{
		return $this->morphMany('App\Comment', 'entity');
	}
	
	public function tags()
	{
		return $this->morphToMany('App\Tag', 'entity', 'taggables');
	}
}
