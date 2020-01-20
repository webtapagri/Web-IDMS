<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RoadPavementProgress;
use App\Models\TRRoadStatus;
use App\Models\VListProgressPerkerasan;
use App\Models\Road;
use App\Models\VRoadLog;
use App\Models\VRoadStatus;
use App\Models\RoadStatus;
use App\Models\RoadCategory;
use App\Models\Block;
use App\Http\Requests\RoadStatusChangesRequest;
use Yajra\DataTables\Facades\DataTables;
use DB;

class TransactionController extends Controller
{
    public function progres_perkerasan(Request $request)
	{
		$access = access($request);
		$data['ctree'] = '/history/progres-perkerasan';
		return view('history.progres_perkerasan', compact('access','data'));
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
		
		$model = VListProgressPerkerasan::whereRaw($where);
		
		$update_action = '';
		$delete_action = '';
		if($access['update']==1 || $access['create']==1){
			$update_action = '
					<button title="Tambah progress perkerasan jalan" class="btn btn-sm btn-primary " onclick="edit({{ $id }}, \'{{ $total_length }}\', \'{{ $curr_progress }}\'); return false;">
						<i class="icon-plus2"></i> Tambah
					</button>
			';
		}
		if($access['delete']==1){
			
		}
		
		$update_action .= '
			<button title="List history perkerasan jalan" class="btn btn-sm btn-info " onclick="detail(this, {{ $id }}, \'{{ $total_length }}\', \'{{ $curr_progress }}\', \'{{ $road_code }}\'); return false;">
				<i class="icon-list3"></i> History
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
			RoadPavementProgress::create($request->all()+['updated_by'=>\Session::get('user_id')]);
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
		$access = access($request);
		$data['ctree'] = '/history/road_status';
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
		$model = VRoadLog::whereRaw($where);
		$update_action = '';
		$delete_action = '';
		if($access['update']==1){
			$update_action = '
					<button title="Tambah perubahan status jalan" class="btn btn-sm btn-primary " onclick="edit({{ $id }}, \'{{ $status_id }}\'); return false;">
						<i class="icon-plus2"></i> Tambah
					</button>
			';
		}
		if($access['delete']==1){
			
		}
		
		$update_action .= '
			<button title="List history status jalan" class="btn btn-sm btn-info " onclick="detail({{ $id }},  \'{{ $status_id }}\'); return false;">
				<i class="icon-list3"></i> History
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
			$land_use_code = '0601';
			$cat = RoadCategory::find($request->category_id);
			$stat = RoadStatus::find($request->status_id);

			$RS = Road::find($request->road_id);
			$BL = Block::where('block_code',$RS->block_code)->first();
			//insert into TM_ROAD
			$esw 				= $RS->werks;
			$blck 				= $BL->block_name;
			// $road_code			= $RS->company_code.$esw.$blck.$land_use_code.$stat->status_code.$cat->category_code.$RS->segment;	
			$road_code			= $esw.$blck.$land_use_code.$stat->status_code.$cat->category_code.$RS->segment;	
			$road_name			= $blck.$cat->category_initial.$RS->segment;
			
			// insert TR_ROAD_STATUS
			TRRoadStatus::create($request->all()+['updated_by'=>\Session::get('user_id'),'road_code'=>$road_code,'road_name'=>$road_name]);
		
			// update TM_ROAD
			$RS->road_code = $road_code;
			$RS->road_name = $road_name;
			$RS->segment = $request->segment;
			$RS->updated_by = \Session::get('user_id');
			$RS->save();

		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
		DB::commit();
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
