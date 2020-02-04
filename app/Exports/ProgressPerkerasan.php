<?php

namespace App\Exports;

use App\Models\VReportProgressPerkerasan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class ProgressPerkerasan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
	
	use Exportable;
	
	public function __construct(array $where)
	{
		$this->where = $where;
	}
	
    public function view():View
    {
		$que 		= json_decode($this->where['que']);
		$que_global = $this->where['que_global'];
		
		$werks = explode(',',session('area_code'));
		$cek =  collect($werks);
		if( $cek->contains('All') ){
			$where = "1=1";
		}else{
			$ww = '';
			foreach($werks as $k=>$w){
				if($w != 'All'){
					$ww .= $k!=0 ? " ,'$w' " : " '$w' ";
				}
			}
			$where = "werks in ($ww)";
		}
		
		$data 		= VReportProgressPerkerasan::whereRaw($where);
		if($que_global){
			$data->whereRaw(" (
						road_code like '%$que_global%' 
						or road_name like '%$que_global%'
						or total_length like '%$que_global%'
						or length like '%$que_global%'
						or month like '%$que_global%'
						or year like '%$que_global%'
						or progress like '%$que_global%'
						or asset_code like '%$que_global%'
						or segment like '%$que_global%'
						or status_name like '%$que_global%'
						or category_name like '%$que_global%'
						or company_name like '%$que_global%'
						or estate_name like '%$que_global%'
						or afdeling_code like '%$que_global%'
						or block_name like '%$que_global%'
						) ");
		}
		
		if( count($que) > 0 ){
			foreach($que as $q){
				$data->where($q->col,'like',"%{$q->val}%");
			}
		}
		
		return view('excel.progress_perkerasan', [
            'data' => $data->get()
        ]);
    }
}
