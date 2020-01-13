<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RoadPavementProgress;
use App\Models\VListProgressPerkerasan;
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

}
