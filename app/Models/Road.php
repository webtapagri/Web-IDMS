<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use DB;

class Road extends Model
{
    use SoftDeletes;
    
	protected $table = 'TM_ROAD';
    
	protected $dates =['deleted_at'];
	
	protected $guarded = ['id'];
	
	protected $appends = [ 'progress','curr_progress'];
	
	public function getProgressAttribute()
    {
		$prg = 0;
        if($this->curr_progress){
			$prg = $this->curr_progress / $this->total_length * 100;
		}
		return round($prg).'%';
	}
	public function getCurrProgressAttribute()
    {
        $get = DB::select( DB::raw("SELECT IFNULL(SUM(LENGTH),0) jml FROM TR_ROAD_PAVEMENT_PROGRESS trpp where trpp.road_id = {$this->id} "));
		if($get){
			return $get[0]->jml;
		}
		return 0;
    }
	
	public function scopeProgress($query): Builder
	{
		$query	->select(['status_name','category_name'])
				->addSelect(DB::raw('TM_ROAD.*'))
				->join('TM_ROAD_STATUS', 'TM_ROAD_STATUS.id', '=', 'TM_ROAD.status_id')
				->join('TM_ROAD_CATEGORY', 'TM_ROAD_CATEGORY.id', '=', 'TM_ROAD.category_id')
				->whereNull('TM_ROAD.deleted_at')
				->where('TM_ROAD_STATUS.status_name','PRODUKSI');
		return $query;
	}
}
