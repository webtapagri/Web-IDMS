<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Models\RoadPavementProgress;
use App\Models\TRRoadStatus;
use App\Models\VListProgressPerkerasan;
use App\Models\Road;
use App\Models\RoadLog;
use App\Models\VRoadLog;
use App\Models\VRoadStatus;
use App\Models\RoadStatus;
use App\Models\RoadCategory;
use App\Models\Block;
use App\Jobs\FlushCache;
use App\Http\Requests\RoadStatusChangesRequest;
use Yajra\DataTables\Facades\DataTables;
use DB;
use URL;
use Session;
use AccessRight;
use App\RoleAccess;
use Illuminate\Support\Arr;
use App\Models\Period;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


class TransactionController extends Controller
{
    public function progres_perkerasan(Request $request)
	{
		$access = access($request);
		$data['ctree'] = '/history/progres-perkerasan';
		return view('history.progres_perkerasan', compact('access','data'));
	}
	
    public function progres_perkerasan_bulkadd(Request $request)
	{
		$access = access($request,'history/progres-perkerasan');
		$back = 'history.progres_perkerasan';
		$data['ctree'] = '/history/progres-perkerasan';
		return view('history.progres_perkerasan_bulk_add', compact('access','data','back'));
	}
    public function progres_perkerasan_bulksave(Request $request)
	{
		ini_set('memory_limit', '-1');
		DB::beginTransaction();
		try{
			$respon['error'] 	= [];
			$respon['success'] 	= [];
			$data = $request->data;
			$err = 0;
			
			if(count($data) > 0){
				foreach($data as $k=>$dt){
					//cek road code 
					$r = Road::where('road_code',$dt['road_code'])->first();
					if(!$r){
						$respon['error'][] = ['value'=>$dt['road_code'],'line'=>($k+1),'status'=>'road code not found'];
						$err += 1;
						continue;
					}
					
					$cek = Period::where([
						'werks'=>$r->werks,
						'month'=>\DateTime::createFromFormat('m', $dt['month'])->format('m'),
						'year'=>$dt['year']
						])->exists();
					if($cek){
						$respon['error'][] = ['value'=>$dt['road_code'],'line'=>($k+1),'status'=>'period has close'];
						$err += 1;
						continue;
					}
					
					// s$cek = Period::selectRaw('max(cast(concat(year , month)as SIGNED )) as close')->where('werks',$r->werks)->first();
					$cek = Period::selectRaw('max(cast(concat(year , month)as SIGNED )) as close')->where('werks',$r->werks)->where('deleted_at',null)->first();
					// dd($cek);
					
					$ym = $dt['year'].\DateTime::createFromFormat('m', $dt['month'])->format('m');
					if($request->validasiClosing!="true"){					
						if($cek){
							if($cek->close == $ym || (int)$ym < $cek->close){
								$respon['error'][] = ['value'=>$dt['road_code'],'line'=>($k+1),'status'=>'period has close'];
								$err += 1;
								continue;
							}else if((int)$ym > ($cek->close+1)){
								if((int)$ym - $cek->close != "89"){
									$respon['error'][] = ['value'=>$dt['road_code'],'line'=>($k+1),'status'=>'period has not yet open'];
									$err += 1;
								}
								continue;
							}
							
						}
						
					}
					
					//cek length
					// $m_progress 	= RoadPavementProgress::selectRaw('ifnull(sum(length),0) progress')->where('road_id',$r->id)->first()->progress;
					$m_progress 	= RoadPavementProgress::selectRaw('ifnull(sum(length),0) progress')->where('road_id',$r->id)->whereraw("cast(concat(year , month)as SIGNED ) < '$ym'" )->first()->progress;
					$m_total_length	= RoadLog::select('total_length')->where('road_id',$r->id)->orderBy('id','desc')->first()->total_length;
					if( ($m_progress+$dt['length']) > $m_total_length ){
						$respon['error'][] = ['value'=>$dt['road_code'],'line'=>($k+1),'status'=>'over length'];
						$err += 1;
						continue;
					}
					
					//cek road status
					$cek_prod_status = TRRoadStatus::select('status_name')
								->join('TM_ROAD_STATUS','TM_ROAD_STATUS.id','=','TR_ROAD_STATUS.status_id')
								->where('road_id',$r->id)
								->orderBy('TR_ROAD_STATUS.id','desc')
								->first();

					if($cek_prod_status['status_name'] != 'PRODUKSI'){
						$respon['error'][] = ['value'=>$dt['road_code'],'line'=>($k+1),'status'=>'road status not production'];
						$err += 1;
						continue;
					}
					
					//fon
					// $disRoad = RoadPavementProgress::firstOrNew( ['road_id'	=>$r->id,'month'=>\DateTime::createFromFormat('m', $dt['month'])->format('m')]+Arr::except($dt, ['month','road_code', 'road_name', 'length']) );
					// $disRoad->length 		= $dt['length'];
					// $disRoad->updated_by 	= \Session::get('user_id');
					// $disRoad->save();
					
				}

				if($err == 0){ 
					foreach($data as $k=>$dt){
						$r = Road::where('road_code',$dt['road_code'])->first();
						// $disRoad = RoadPavementProgress::firstOrNew( ['road_id'	=>$r->id,'month'=>\DateTime::createFromFormat('m', $dt['month'])->format('m')]+Arr::except($dt, ['month','road_code', 'road_name', 'length']) );
						$disRoad = RoadPavementProgress::updateOrCreate( ['road_id'	=>$r->id,'month'=>\DateTime::createFromFormat('m', $dt['month'])->format('m')]+Arr::except($dt, ['month','road_code', 'road_name', 'length']) );
						$disRoad->length 		= $dt['length'];
						$disRoad->updated_by 	= \Session::get('user_id');
						$disRoad->save();
						$respon['success'][] = $dt['road_code'];
					}
				}

			}
			
		}catch (\Throwable $e) {
			DB::rollBack();
            return response()->error('Error',throwable_msg($e));
        }catch (\Exception $e) {
			DB::rollBack();
            return response()->error('Error',exception_msg($e));
		}
		DB::commit();
		dispatch((new FlushCache)->onQueue('low'));
		return response()->success('Success', $respon);
	}
	
	public function progres_perkerasan_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'history/progres-perkerasan');
		
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
		
		// $model = VListProgressPerkerasan::whereRaw($where);
		$model = Road::progress()->whereRaw($where);
		
		$update_action = '';
		$delete_action = '';
		if($access['update']==1 || $access['create']==1){
			$update_action = '
					<button title="Tambah progress perkerasan jalan"  class="btn btn-sm btn-primary " data-toggle="tooltip" data-placement="top" onclick="edit({{ $id }}, \'{{ $total_length }}\', \'{{ $curr_progress }}\'); return false;">
						<i class="icon-plus2"></i>
					</button>
			';
		}
		if($access['delete']==1){
			
		}
		
		$update_action .= '
			<button title="List history perkerasan jalan"  class="btn btn-sm btn-info " data-toggle="tooltip" data-placement="top" onclick="detail(this, {{ $id }}, \'{{ $total_length }}\', \'{{ $curr_progress }}\', \'{{ $road_code }}\'); return false;">
				<i class="icon-list3"></i>
			</button>
		';
		
		return Datatables::eloquent($model)
			->addColumn('action', '<div class="">
					'.$update_action.'
					'.$delete_action.'
				<div>
				')
			->rawColumns(['action'])
			->make(true);
	}
	
	public function progres_perkerasan_update(Request $request)
	{
		try {

			$RS = Road::find($request->road_id);
			///	where('start_valid','<=',date("Y-m-d"))->where('end_valid','>=',date("Y-m-d")) // block_active
			$block_inactive = Block::where('id',$RS->block_id)->where('werks', $RS->werks)->where('start_valid','<=',date("Y-m-d"))->where('end_valid','<=',date("Y-m-d"))->exists();  //block_inactive
			if ($block_inactive == "true"){
				throw new \ErrorException('Block Sudah Tidak Aktif');
			}

			//cek periode close
			if($werks = $RS){
				$cek = Period::selectRaw('max(cast(concat(year , month)as SIGNED )) as close')->where('werks',$werks->werks)->first();
				if($cek){
					
					if($cek->close == ( $request->year.$request->month ) || (int)( $request->year.$request->month ) < $cek->close){
						throw new \ErrorException("Priode {$request->month} {$request->year} untuk BA {$werks->werks} telah ditutup");
					}else if((int) ( $request->year.$request->month ) > ($cek->close+1)){
						throw new \ErrorException("Priode {$request->month} {$request->year} untuk BA {$werks->werks} tidak boleh menginput 2 bulan setelah periode di close");
					}
					
				}

			}
			//disini
			$ym  = $request->year.$request->month;
			$m_progress 	= RoadPavementProgress::selectRaw('ifnull(sum(length),0) progress')->where('road_id',$request->road_id)->whereraw("cast(concat(year , month)as SIGNED ) < '$ym'" )->first()->progress;
			// $m_total_length	= RoadLog::select('total_length')->where('road_id',$request->road_id)->orderBy('id','desc')->first()->total_length;
			if( ($m_progress+$request->length) > $RS->total_length ){
				throw new \ErrorException("Please check your data, Road Code '$RS->road_code' is over length");
			}
				// dd($request->road_id,$m_progress,$request->length,$RS->total_length);
			RoadPavementProgress::updateOrCreate(['road_id'=> $request->road_id, 'month'=> $request->month, 'year'=> $request->year],$request->all()+['updated_by'=>\Session::get('user_id')]);
			// RoadPavementProgress::create($request->all()+['updated_by'=>\Session::get('user_id')]);
			
			dispatch((new FlushCache)->onQueue('low'));
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil mengupdate data');
        return redirect()->route('history.progres_perkerasan');
	}
		
	//STart API
	
	public function api_progress_perkerasan_detail(Request $request, $id)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'history/progres-perkerasan');
		$model = RoadPavementProgress::with('admin')
				->orderBy('year','asc')
				->orderBy('month','asc')
				->where('road_id',$id);
		
		$update_action = '';
		$delete_action = '';
		
		return Datatables::eloquent($model)
			->editColumn('updated_by','{{ $admin[\'name\'] }}')
			->rawColumns(['action'])
			->make(true);
	}

	/////
	public function road_status(Request $request)
	{
		// if (empty(Session::get('authenticated'))){
		// 	return redirect('/login');
		// }
		// if (AccessRight::granted($request) === false) {
		// 	$data['page_title'] = 'Oops! Unauthorized.';
		// 	return response(view('errors.403')->with(compact('data')), 403);
		// }
		$access = access($request);
		$data['ctree'] = '/history/road-status';
		return view('history.road_status', compact('access','data'));
	}
	
	public function road_status_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'history/road-status');
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
		// $model = VRoadLog::whereRaw($where);
		$model = Road::perubahanStatus()->whereRaw($where);
		$update_action = '';
		$delete_action = '';
		if($access['update']==1){
			$update_action = '
					<button title="Tambah perubahan status jalan"  class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" onclick="edit({{ $id }}, \'{{ $status_id }}\'); return false;">
						<i class="icon-plus2"></i>
					</button>
			';
		}
		if($access['delete']==1){
			
		}
		
		$update_action .= '
			<button title="List history status jalan"  class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Edit" onclick="detail(this,{{ $id }},  \'{{ $status_id }}\'); return false;">
				<i class="icon-list3"></i>
			</button>
		';
		
		return Datatables::eloquent($model)
			->addColumn('action', '<div class="">
					'.$update_action.'
					'.$delete_action.'
				<div>
				')
			->rawColumns(['action'])
			->make(true);
	}
	
	public function road_status_update(Request $request)
	{
		DB::beginTransaction();
		try {

			$request->validate([
				'status_id' => 'required',
				'category_id' => 'required',
				'segment' => 'required|numeric|max:9|min:1'
			]);
			// dd($request);

			$land_use_code = '0601';
			$cat = RoadCategory::find($request->category_id);
			$stat = RoadStatus::find($request->status_id);

			$RS = Road::find($request->road_id);
			// $BL = Block::where('block_code',$RS->block_code)->where('werks', $RS->werks)->first(); //sebelum validasi block end_valid
			
			//validasi block
			// $BL = Block::where('block_code',$RS->block_code)->where('werks', $RS->werks)->whereBetween('end_valid', [date("Y-m-d"), '9999-12-31'])->first();
			$BL = Block::where('block_code',$RS->block_code)->where('werks', $RS->werks)->where('start_valid','<=',date("Y-m-d"))->where('end_valid','>=',date("Y-m-d"))->first();
			// today < end_validate < 9999-12-31
			// 2020-02-19 < 2020-03-01 < 9999-12-31 => block ini masih bisa digunakan
			// 2020-02-19 < 2020-01-31 < 9999-12-31 => block ini sudah tidak dapat digunakan, gunakan block baru yang aktif

			//start_valid <= today and end_valid >= today
			//2019-12-01 <= 2020-02-26 and 9999-12-31 >= 2020-02-26  true
			//2020-01-01 <= 2020-02-26 and 9999-12-31 >= 2020-02-26  true
			//ambil yg start_validnya paling baru dan start_valid <= today and end_valid >= today
			
			//insert into TM_ROAD
			$company 			= $RS->company_code;
			$estate 			= $RS->estate_code;
			$blc 				= $BL->block_name;
			$blck 				= $RS->block_code;
			
			$road_code			= $company.$estate.$blck.$land_use_code.$stat->status_code.$cat->category_code.$request->segment;	
			$road_name			= $blc.$cat->category_initial.$request->segment;
			

			if (Road::where('road_name', '=', $road_name)->exists() == "true"){
				throw new \ErrorException('Segment sudah digunakan');
			}

			// insert TR_ROAD_STATUS
			TRRoadStatus::create($request->all()+['updated_by'=>\Session::get('user_id'),'road_code'=>$road_code,'road_name'=>$road_name]);
		
			// update TM_ROAD
			$RS->road_code 		= $road_code;
			$RS->road_name		= $road_name;
			$RS->segment 		= $request->segment;
			$RS->status_id 		= $request->status_id;
			$RS->category_id 	= $request->category_id;
			$RS->updated_by 	= \Session::get('user_id');
			$RS->save();

		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
		DB::commit();
		
		dispatch((new FlushCache)->onQueue('low'));
			
		\Session::flash('success', 'Berhasil mengupdate data');
        return redirect()->route('history.road_status');
	}
	
	
	//STart API
	
	public function api_road_status_detail(Request $request, $id)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'history/road-status');
		$model = VRoadStatus::with('admin')
				->orderBy('updated_at','asc')
				->where('road_id',$id);
		
		$update_action = '';
		$delete_action = '';
		
		return Datatables::eloquent($model)
			->editColumn('updated_by','{{ $admin[\'name\'] }}')
			->rawColumns(['action'])
			->make(true);
	}

}
