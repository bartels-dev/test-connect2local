<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetPhoto extends Model
{
	public function set()
	{
		return $this->belongsTo('\App\Set', 'set_id');
	}
	
    public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function comments()
	{
		return $this->morphMany('App\Comment', 'entity');
	}
}
