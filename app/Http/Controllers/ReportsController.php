<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VRoad;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use AccessRight;
use App\RoleAccess;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Facades\Buttons;
use App\DataTables\RoadDataTable;
use URL;
use DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
	//
	public function index(RoadDataTable $dataTable)
    {
        return $dataTable->render('report.road_master');
    }
	
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
			// ->parameters([
			// 	'dom' => 'Blfrtip',
			// 	'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset', 'reload'],
			// ])
			->make(true);
	}

	public function downloadExcel() {
		return Excel::download(new RequestExport(), 'road_master.xlsx');
	 }
}
