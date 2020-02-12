<?php

namespace App\Exports;

use App\Models\VRoad;
use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;


class RoadMaster implements FromQuery, WithHeadings
{
 	/**
    * @return \Illuminate\Support\Collection
    */
	
	use Exportable;

	public function __construct(array $where)
	{
		$this->where = $where;
	}
	
    public function query()
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
		\Config::set('excel.exports.csv.delimiter', ';');
		$data = VRoad::query()->select('company_name','estate_name','afdeling_name','block_code','status_name','category_name','segment','werks','road_name','road_code','total_length','asset_code')->whereRaw($where);
		// $data 		= VRoad::whereRaw($where);
		if($que_global){
			$data->whereRaw(" (
						estate_name like '%$que_global%' 
						or werks like '%$que_global%'
						or afdeling_code like '%$que_global%'
						or block_code like '%$que_global%'
						or block_name like '%$que_global%'
						or status_name like '%$que_global%'
						or category_name like '%$que_global%'
						or segment like '%$que_global%'
						or road_name like '%$que_global%'
						or road_code like '%$que_global%'
						or total_length like '%$que_global%'
						or asset_code like '%$que_global%'
						) ");
		}
		
		if( count($que) > 0 ){
			foreach($que as $q){
				$data->where($q->col,'like',"%{$q->val}%");
			}
		}
		
		return $data;
	}
	
	public function headings(): array
    {

        return [
            'Company',
            'Estate',
            'Afdeling',
            'Block',
            'Status',
            'Category',
            'Segment',
            'BA Code',
            'Road Name',
            'Road Code',
            'Length',
            'Asset Code',

        ];
    }
}



// class RoadMaster implements FromView
// {
//  	/**
//     * @return \Illuminate\Support\Collection
//     */
	
// 	use Exportable;

// 	public function __construct(array $where)
// 	{
// 		$this->where = $where;
// 	}
	
//     public function view():View
//     {
// 		$que 		= json_decode($this->where['que']);
// 		$que_global = $this->where['que_global'];
		
// 		$werks = explode(',',session('area_code'));
// 		$cek =  collect($werks);
// 		if( $cek->contains('All') ){
// 			$where = "1=1";
// 		}else{
// 			$ww = '';
// 			foreach($werks as $k=>$w){
// 				if($w != 'All'){
// 					$ww .= $k!=0 ? " ,'$w' " : " '$w' ";
// 				}
// 			}
// 			$where = "werks in ($ww)";
// 		}
		
// 		$data 		= VRoad::whereRaw($where);
// 		if($que_global){
// 			$data->whereRaw(" (
// 						estate_name like '%$que_global%' 
// 						or werks like '%$que_global%'
// 						or afdeling_code like '%$que_global%'
// 						or block_code like '%$que_global%'
// 						or block_name like '%$que_global%'
// 						or status_name like '%$que_global%'
// 						or category_name like '%$que_global%'
// 						or segment like '%$que_global%'
// 						or road_name like '%$que_global%'
// 						or road_code like '%$que_global%'
// 						or total_length like '%$que_global%'
// 						or asset_code like '%$que_global%'
// 						) ");
// 		}
		
// 		if( count($que) > 0 ){
// 			foreach($que as $q){
// 				$data->where($q->col,'like',"%{$q->val}%");
// 			}
// 		}
		
// 		return view('excel.road_master', [
//             'data' => $data->get()
//         ]);
//     }
// }