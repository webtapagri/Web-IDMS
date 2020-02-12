<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use AccessRight;
use App\RoleAccess;
use URL;
use API;
use DB;
use App\Http\Requests\ConfigRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Estate;
use App\Models\Period;
use Carbon\Carbon;

class ConfigurationController extends Controller
{
    //
    public function period()
	{
		$access = AccessRight::roleaccess();
		$title = 'Period Configuration';
		$data['ctree'] = '/setting/period';
		$data["access"] = (object)$access['access'];
		return view('setting.period', compact('data','title'));
	}
	
	public function period_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'setting/period');
		
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
			// $where = "company_id in (select distinct company_id from TM_ESTATE where werks in ($ww))";
			$where = "trp.werks in ($ww)";
		}

		$model = DB::select( DB::raw('select @rank  := ifnull(@rank, '.$start.')  + 1  AS no, trp.*, tme.estate_name from TR_PERIOD as trp join TM_ESTATE as tme on trp.werks = tme.werks  where trp.deleted_at is null and '.$where));
		$update_action ="";
		$delete_action ="";

		if($access['update']==1){
			$update_action ='<button class="btn btn-link text-primary-600" onclick="edit({{ $id }}, \'{{ $werks }}\', \'{{ $month }}\', \'{{ $year }}\'); return false;">
								<i class="icon-pencil7"></i> Edit
							</button>';
		}
		if($access['delete']==1){
			$delete_action = '<a class="btn btn-link text-danger-600" href="" onclick="del(\''.URL::to('setting/period-delete/{{ $id }}').'\'); return false;">
								<i class="icon-trash"></i> Hapus
							</a>';
		}

		
		$collection = collect($model);
		return Datatables::of($collection)
			->addColumn('action', '<div class="text-center">'.
						$update_action.
						$delete_action.
						'<div>')
			->rawColumns(['action'])
			->make(true);
	}

	public function period_save(ConfigRequest $request)
	{
		try {

			$cek = Period::where('werks',$request->werks)
					->where('month',$request->month)
					->where('year',$request->year)
					->count();
			if($cek > 0){
				throw new \Exception('Data closing period sudah ditambahkan .');
			}

			Period::create($request->only('id','werks','month','year'));
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil menyimpan data');
        return redirect()->route('setting.period');
	}

	public function period_update(ConfigRequest $request)
	{

		try {
			$cek = Period::where('werks',$request->werks)
					->where('month',$request->month)
					->where('year',$request->year)
					->count();
			if($cek > 0){
				throw new \Exception('Data closing period sudah ditambahkan .');
			}
			$CP = Period::find($request->id);
			$CP->werks = $request->werks;
			$CP->month = $request->month;
			$CP->year = $request->year;
			$CP->updated_by = \Session::get('user_id');
			$CP->save();
		}catch (\Throwable $e) {
			\Session::flash('error', throwable_msg($e));
			return redirect()->back()->withInput($request->input());
		}catch (\Exception $e) {
			\Session::flash('error', exception_msg($e));
			return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil mengupdate data');
		return redirect()->route('setting.period');

	}

	
	public function api_werks(Request $request)
	{
		try {
				$req = $request->all();
				$access = access($request, 'setting/period');
				
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
				
				// $get = Estate::whereRaw("deleted_at is null and $where")->orderBy('id','desc');
				$get = DB::select( DB::raw('select * from TM_ESTATE where deleted_at is null and '.$where));
				
				// $get = Estate::all();
			
		}catch (\Throwable $e) {
            return response()->error('Error',throwable_msg($e));
        }catch (\Exception $e) {
            return response()->error('Error',exception_msg($e));
		}
		
		return response()->success('Success', $get);
	}

	public function delete($id){
		
		try {
			$data = Period::find($id);
			$data->deleted_at = Carbon::now();
			$data->updated_by = \Session::get('user_id');
			$data->save();
			
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil menghapus data');
        return redirect()->route('setting.period');
	}

}
