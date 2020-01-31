<?php

namespace App\Exports;

use App\Models\VRoad;
use Illuminate\Http\Request;
use App\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Session;
use AccessRight;
use App\RoleAccess;
use URL;
use DB;
use Carbon\Carbon;

class RoadMaster implements FromCollection, WithHeadings
{

	public function collection()
    {
		// $where =  $this->reqdata();
		// $coll = VRoad::whereRaw("deleted_at is null and $where")->orderBy('id','desc');
		$coll = VRoad::all();
		// foreach($coll->data as $dt){
		// 	$data[] = array (
		// 		"estate" => $dt->estate_name,
		// 		"ba_code" => $dt->werks,
		// 		"afdeling" => $dt->afdeling_name,
		// 		"block_code" => $dt->block_code,
		// 		"block_name" => $dt->block_name,
		// 		"status" => $dt->status_name,
		// 		"category" => $dt->category,
		// 		"segment" => $dt->segment,
		// 		"road_name" => $dt->road_name,
		// 		"road_code" => $dt->road_code,
		// 		"length" => $dt->total_length,
		// 		"asset_code" => $dt->asset_code,
		// 	);
		// }
		// dd($data);
	    return $coll;
	}


	public function reqdata(Request $request){
		
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'report/road');
		
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
		
	}

	public function headings(): array
    {
        return [
			'Estate',
            'BA Code',
            'Afdeling',
            'Block Code',
            'Block Name',
            'Status',
            'Category',
            'Segment', 
            'Road Name',
            'Road Code',
            'Length',
            'Asset Code',
        ];
    }
	
    // /**
    // * @return \Illuminate\Support\Collection
    // */
	
	// use Exportable;
	
	// public function __construct(string $request)
	// {
	// 	$this->request => $request;
	// }
	
    // public function view():View
    // {
		
	// 	$werks => explode(',',session('area_code'));
	// 	$cek =>  collect($werks);
	// 	if( $cek->contains('All') ){
	// 		$where => "1=>1";
	// 	}else{
	// 		$ww => '';
	// 		foreach($werks as $k=>>$w){
	// 			if($w !=> 'All'){
	// 				$ww .=> $k!=>0 ? " ,'$w' " : " '$w' ";
	// 			}
	// 		}
	// 		$where => "werks in ($ww)";
	// 	}
		
	// 	$model => VRoad::whereRaw("deleted_at is null and $where")->orderBy('id','desc');


	// 	return view('excel.road_master', [
    //         'data' =>> VRoad::all()
    //     ]);
    // }
}
