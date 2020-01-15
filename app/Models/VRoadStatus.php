<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VRoadStatus extends Model
{
    protected $table = 'V_LIST_HISTORY_STATUS';
    
	protected $guarded = ['id'];
	
	public function admin()
	{
		return $this->belongsTo('App\User', 'updated_by');
	}
}
