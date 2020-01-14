<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RoadPavementProgress;
use App\Models\RoadLog;
use App\Models\VListProgressPerkerasan;
use App\Models\VRoadLog;
use Yajra\DataTables\Facades\DataTables;

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
		$model = VListProgressPerkerasan::whereRaw('1=1')->orderBy('id','desc');
		
		$update_action = '';
		$delete_action = '';
		if($access['update']==1){
			$update_action = '
					<button title="Tambah progress perkerasan jalan" class="btn btn-sm btn-primary " onclick="edit({{ $id }}, \'{{ $total_length }}\', \'{{ $curr_progress }}\'); return false;">
						<i class="icon-plus2"></i> Tambah
					</button>
			';
		}
		if($access['delete']==1){
			
		}
		
		$update_action .= '
			<button title="List history perkerasan jalan" class="btn btn-sm btn-info " onclick="detail({{ $id }}, \'{{ $total_length }}\', \'{{ $curr_progress }}\'); return false;">
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
				->orderBy('id','desc')
				->where('road_id',$id);
		
		$update_action = '';
		$delete_action = '';
		
		return Datatables::eloquent($model)
			->editColumn('updated_by','{{ $admin[\'name\'] }}')
			->rawColumns(['action'])
			->make(true);
	}

	/////
	public function road_log(Request $request)
	{
		$access = access($request);
		$data['ctree'] = '/history/road_log';
		return view('history.road_log', compact('access','data'));
	}
	
	public function road_log_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'history/road-log');
		$model = VRoadLog::whereRaw('1=1')->orderBy('id','desc');
		
		$update_action = '';
		$delete_action = '';
		if($access['update']==1){
			$update_action = '
					<button title="Tambah log status jalan" class="btn btn-sm btn-primary " onclick="edit({{ $id }}, \'{{ $total_length }}\'); return false;">
						<i class="icon-plus2"></i> Tambah
					</button>
			';
		}
		if($access['delete']==1){
			
		}
		
		$update_action .= '
			<button title="List history status jalan" class="btn btn-sm btn-info " onclick="detail({{ $id }}, \'{{ $total_length }}\'); return false;">
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
	
	public function road_log_update(Request $request)
	{
		try {
			RoadLog::create($request->all()+['updated_by'=>\Session::get('user_id')]);
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil mengupdate data');
        return redirect()->route('history.road_log');
	}
	
	
	//STart API
	
	public function api_road_log_detail(Request $request, $id)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'history/road-log');
		$model = RoadLog::with('admin')
				->orderBy('id','desc')
				->where('road_id',$id);
		
		$update_action = '';
		$delete_action = '';
		
		return Datatables::eloquent($model)
			->editColumn('updated_by','{{ $admin[\'name\'] }}')
			->rawColumns(['action'])
			->make(true);
	}

}
