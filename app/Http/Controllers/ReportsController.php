<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VRoad;
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
}
