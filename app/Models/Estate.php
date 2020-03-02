<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estate extends Model
{
    use SoftDeletes;
    
	protected $table = 'TM_ESTATE';
	
    protected $dates =['deleted_at'];
	
	protected $fillable = [
		'estate_code',
		'estate_name',
		'company_id',
		'werks',
		'city',
		'region_code',
		'start_valid',
		'end_valid',
		'insert_time_dw',
		'update_time_dw',
	];
	
	public function company()
	{
		return $this->belongsTo('App\Models\Company', 'company_id');
	}
}
