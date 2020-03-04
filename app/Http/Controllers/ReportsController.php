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
use App\Models\Company;
use App\Models\Estate;
use App\Models\Afdeling;
use App\Models\Block;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProgressPerkerasan;
use App\Exports\RoadMaster;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Input;
use NahidulHasan\Html2pdf\Facades\Pdf;

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
	
	
	public function x_progress_perkerasan_download(Request $request)
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
	
	public function progress_perkerasan_download(Request $request)
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
				'Month',
				'Year',
				'Status',
				'Category',
				'Segment',
				'Road Name',
				'Road Code',
				'Length',
				'Pavement Length (m)',
				'Asset Code',
            ],';');			
			
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
			
            foreach ($data->get() as $data) {
                // Add a new row with data
                fputcsv($handle, [
                    $data->company_name,
					$data->estate_name,
					$data->afdeling_code,
					$data->block_name,
					$data->month,
					$data->year,
					$data->status_name,
					$data->category_name,
					$data->segment,
					$data->road_name,
					$data->road_code,
					$data->total_length,
					$data->length,
					$data->asset_code
                ], ';');
            }

            // Close the output stream
            fclose($handle);
        }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="REPORT_ROAD_PAVEMENT_PROGRESS_'.date('Ymd').'.csv"',
            ]);

        return $response;
	}

	
	public function xx_download_road(Request $request)
	{
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', 0);
		try {
			if($request->exp == "csvlama"){
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
			}else{
				$where = $request->all();			
				$file = 'REPORT_ROAD_MASTER_'.date('Ymd').'.csv';
				return (new RoadMaster($where))->download($file);
			}
			
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
	
	public function download_road(Request $request)
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
            ], ';');			
			
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
                ], ';');
            }

            // Close the output stream
            fclose($handle);
        }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="REPORT_ROAD_MASTER_'.date('Ymd').'.csv"',
            ]);

        return $response;
	}
	



	
    public function summary(Request $request)
	{
		$access = AccessRight::roleaccess();
		$title = 'Pavement Summary';
		$data['ctree'] = '/report/summary';
		$data["access"] = (object)$access['access'];
		return view('report.summary', compact('data','title'));
    }
	
	
	public function get_year()
	{
		try{
		
			$data = VReportProgressPerkerasan::select(DB::raw('distinct year as tahun'))->get();
			
		}catch (\Throwable $e) {
            return response()->error('Error',throwable_msg($e));
        }catch (\Exception $e) {
            return response()->error('Error',exception_msg($e));
		}
		return response()->success('Success', $data);
	}


	
    public function summary_data($werks, $year)
	{
		$werks ;
		if($year == 0){
			$tahun = date('Y');
		}else{
			$tahun = $year ;
		}
			
			$access = AccessRight::roleaccess();

			$plant = Estate::select(DB::raw('concat(werks," - ",estate_name) as business_area'))
                           ->whereRaw("werks = $werks")
						   ->pluck('business_area');
							
			$products = DB::table('TM_ROAD AS tr')
							->join('TM_ROAD_STATUS AS trs', 'tr.status_id', '=', 'trs.id')
							->selectRaw('COUNT(DISTINCT road_code) AS jum_produksi, SUM(total_length) AS len_produksi')
							->whereRaw ("tr.deleted_at IS NULL AND status_name = 'PRODUKSI' AND werks = $werks")
							->get();

			$pavement = DB::table('TM_ROAD AS tr')
							->join('TM_ROAD_STATUS AS trs', 'tr.status_id', '=', 'trs.id')
							->join('TR_ROAD_PAVEMENT_PROGRESS AS trpp', 'tr.id', '=', 'trpp.road_id')
							->selectRaw('SUM(length) AS len_pavement')
							->whereRaw ("tr.deleted_at IS NULL AND status_name = 'PRODUKSI' AND werks = $werks")
							->get();

			$nonprod = DB::table('TM_ROAD AS tr')
							->join('TM_ROAD_STATUS AS trs', 'tr.status_id', '=', 'trs.id')
							->selectRaw('COUNT(DISTINCT road_code) AS jum_non_produksi,SUM(total_length) AS len_non_produksi')
							->whereRaw ("tr.deleted_at IS NULL AND status_name = 'NON PRODUKSI' AND werks = $werks")
							->get();

			$umum = DB::table('TM_ROAD AS tr')
							->join('TM_ROAD_STATUS AS trs', 'tr.status_id', '=', 'trs.id')
							->selectRaw('COUNT(DISTINCT road_code) AS jum_umum, SUM(total_length) AS len_umum')
							->whereRaw ("tr.deleted_at IS NULL AND status_name = 'UMUM' AND werks = $werks")
							->get();



			$dp = DB::table('TR_ROAD_PAVEMENT_PROGRESS AS trpp')
							->join('TM_ROAD AS tr', 'tr.id', '=', 'trpp.road_id')
							->selectRaw("SUM(length) AS len_perkerasan, MONTHNAME(str_to_date(month, '%m')) AS bulan")
							->whereRaw ("tr.werks = $werks AND year = $tahun")
							->groupBy('month')
							->orderBy('month','ASC')
							->get();
				
			$data['werks'] = $plant;
			$data['year'] = $tahun;	
			$data['jum_produksi'] = (int)$products[0]->jum_produksi;
			$data['len_produksi'] = (int)$products[0]->len_produksi;
			$data['len_pavement'] = (int)$pavement[0]->len_pavement;
			$data['jum_non_produksi'] = (int)$nonprod[0]->jum_non_produksi;
			$data['len_non_produksi'] = (int)$nonprod[0]->len_non_produksi;
			$data['jum_umum'] = (int)$umum[0]->jum_umum;
			$data['len_umum'] = (int)$umum[0]->len_umum;
			$data['perkerasan'] = $dp;
			$view = view("report.summary_perkerasan",compact('data'))->render();

			return response()->json(['html'=>$view]);
	}
	
    // function print_pdf($data){
	// 	$document =  Pdf::generatePdf(view('report.summary_perkerasan',compact('data')));
	// }
	
}
