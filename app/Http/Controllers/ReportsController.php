<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Road;
use App\Models\VRoad;
use App\Models\VReportProgressPerkerasan;
use Yajra\DataTables\Facades\DataTables;
use Session;
use AccessRight;
use App\RoleAccess;
use URL;
use DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProgressPerkerasan;
use App\Exports\RoadMaster;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
		
		$model = VRoad::whereRaw("$where")
					// ->orderBy('id','desc')
					;
		
		return Datatables::eloquent($model)
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
		
		//blm d optimz
		$model = VReportProgressPerkerasan::whereRaw($where);
		
		
		return Datatables::eloquent($model)
			
			->rawColumns(['action'])
			->make(true);
	}
	
	
	public function progress_perkerasan_download(Request $request)
	{
		try {
			$where = $request->all();			
			$file = 'REPORT_PROGRESS_PERKERASAN_JALAN_'.date('Ymd').'.xlsx';
			return Excel::download(new ProgressPerkerasan($where), $file);
			
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
	}

	
	public function download_road(Request $request)
	{
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 0);
		try {
			$where = $request->all();			
			$file = 'REPORT_ROAD_MASTER_'.date('Ymd').'.csv';
			return (new RoadMaster($where))->download($file);
			
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
	}

	public function x_download_road(Request $request)
	{
		ini_set('memory_limit', '-1');
		try {
			$where = $request->all();			
			$file = 'REPORT_ROAD_MASTER_'.date('Ymd').'.xlsx';
			return Excel::download(new RoadMaster($where), $file);
			
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
	}
	
	public function xx_download_road(Request $request)
	{
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 0);
		$post = $request->all();
		
		$que 		= json_decode($post['que']);
		$que_global = $post['que_global'];
		
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
		
		
		$response = new StreamedResponse(function() use($where,$que, $que_global){
            // Open output stream
            $handle = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($handle, [
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
            ]);			
			
			$data 		= VRoad::whereRaw($where);
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
			if($que){
				if( count($que) > 0 ){
					foreach($que as $q){
						if($q->col=='status_name'){
							$data->where($q->col,$q->val);
						}else{
							$data->where($q->col,'like',"%{$q->val}%");
						}
					}
				}
			}
			
            foreach ($data->get() as $data) {
                // Add a new row with data
                fputcsv($handle, [
                    $data->company_name,
					$data->estate_name,
					$data->afdeling_code,
					$data->block_code,
					$data->status_name,
					$data->category_name,
					$data->segment,
					$data->werks,
					$data->road_name,
					$data->road_code,
					$data->total_length,
					$data->asset_code
                ]);
            }

            // Close the output stream
            fclose($handle);
        }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="REPORT_ROAD_MASTER_'.date('Ymd').'.csv"',
            ]);

        return $response;
	}
	
	
}
