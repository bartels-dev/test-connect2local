<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
	
    public function entity()
	{
		//Returns the entity the comment is linked to
		return $this->morphTo();
	}
	
	public function replies()
	{
		return $this->hasMany(Comment::class, 'parent_id');
	}
}
