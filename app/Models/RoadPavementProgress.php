<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoadPavementProgress extends Model
{
    protected $table = 'TR_ROAD_PAVEMENT_PROGRESS';
	
	protected $guarded = ['id'];
	
	public function admin()
	{
		return $this->belongsTo('App\User', 'updated_by');
	}
}
