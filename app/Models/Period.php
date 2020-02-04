<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Period extends Model
{
	use SoftDeletes;
    
	protected $table = 'TR_PERIOD';
    
	protected $dates =['deleted_at'];
	
	protected $fillable = [
		'werks',
		'month',
		'year',
	];

	
}
