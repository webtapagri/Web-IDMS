<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VRoad;
use App\Models\VListProgressPerkerasan;
use Session;
use AccessRight;
use App\RoleAccess;
use Yajra\DataTables\Facades\DataTables;
use URL;
use DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    //
    public function road(Request $request)
	{
		$access = AccessRight::roleaccess();
		$title = 'Road Status list';
		$data['ctree'] = '/report/road';
		$data["access"] = (object)$access['access'];
		return view('report.road', compact('data','title'));
    }
    
    public function road_datatables(Request $request)
	{
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
		
		$model = VRoad::whereRaw("deleted_at is null and $where")->orderBy('id','desc');
		
		
		return Datatables::eloquent($model)
			// ->addColumn('action', '<div class="">
			// 		'.$update_action.'
			// 		'.$delete_action.'
			// 	<div>
			// 	')
			// ->rawColumns(['action'])
			->make(true);
	}
	
	public function progress_perkerasan(Request $request)
	{
		$access = access($request);
		$data['ctree'] = '/report/progress-perkerasan';
		return view('report.progress_perkerasan', compact('access','data'));
	}
	
	public function progress_perkerasan_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'report/progress-perkerasan');
		
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
		
		$model = VListProgressPerkerasan::whereRaw($where);
		
		
		$update_action = '
			<button title="List history perkerasan jalan" class="btn btn-sm btn-info " onclick="detail(this, {{ $id }}, \'{{ $total_length }}\', \'{{ $curr_progress }}\', \'{{ $road_code }}\'); return false;">
				<i class="icon-list3"></i> History
			</button>
		';
		
		return Datatables::eloquent($model)
			->addColumn('action', '<div class="">
					'.$update_action.'
				<div>
				')
			->rawColumns(['action'])
			->make(true);
	}
	
	
	
	
}
