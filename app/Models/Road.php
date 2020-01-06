<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Road extends Model
{
    use SoftDeletes;
    
	protected $table = 'TM_ROAD';
    
	protected $dates =['deleted_at'];
	
	protected $guarded = ['id'];
	
}
