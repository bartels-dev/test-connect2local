<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function photos()
	{
		return $this->morphedByMany('App\Photo', 'entity', 'taggables');
	}
	
	public function sets()
	{
		return $this->morphedByMany('App\Set', 'entity', 'taggables');
	}
	
	public function videos()
	{
		return $this->morphedByMany('App\Video', 'entity', 'taggables');
	}
}
