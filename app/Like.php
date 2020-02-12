<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public function user()
	{
		return $this->belongsTo('App\User', 'user_id');
	}
	
    public function entity()
	{
		//Returns the entity the like is linked to
		return $this->morphTo();
	}
}
