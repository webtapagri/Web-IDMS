<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RoadStatus;
use App\Models\RoadCategory;
use App\Models\VRoadCategory;
use App\Models\Road;
use App\Models\VRoad;
use App\Models\RoadLog;
use App\Models\TRRoadStatus;
use App\Models\GeneralData;
use App\Models\Company;
use App\Models\Estate;
use App\Models\Afdeling;
use App\Models\Block;
use Session;
use AccessRight;
use App\RoleAccess;
use API;
use App\Http\Requests\RoadStatusRequest;
use App\Http\Requests\RoadCategoryRequest;
use App\Http\Requests\RoadRequest;
use Yajra\DataTables\Facades\DataTables;
use URL;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class RoadController extends Controller
{
    public function index()
	{
		return view('road.index');
	}
    public function status(Request $request)
	{
            // $data = $request->session()->all();
			// dd($data);
		$access = AccessRight::roleaccess();
		$title = 'Road Status list';
		$data['ctree'] = '/master/road-status';
		$data["access"] = (object)$access['access'];
		return view('road.status', compact('data','title'));
	}
	
	public function api_status(Request $request)
	{
		try {
			
			$get = RoadStatus::all();
			
		}catch (\Throwable $e) {
            return response()->error('Error',throwable_msg($e));
        }catch (\Exception $e) {
            return response()->error('Error',exception_msg($e));
		}
		
		return response()->success('Success', $get);
	}
	
	public function api_category($id)
	{
		try {
			
			$get = RoadCategory::where('status_id',$id)->get();
		}catch (\Throwable $e) {
            return response()->error('Error',throwable_msg($e));
        }catch (\Exception $e) {
            return response()->error('Error',exception_msg($e));
		}
		
		return response()->success('Success', $get);
	}

	
	public function status_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'master/road-status');
		$model = RoadStatus::selectRaw(' @rank  := ifnull(@rank, '.$start.')  + 1 AS no, TM_ROAD_STATUS.*')->whereRaw('1=1');
		$update_action ="";
		$delete_action ="";

		if($access['update']==1){
			$update_action ='<button class="btn btn-link text-primary-600" onclick="edit({{ $id }}, \'{{ $status_name }}\', \'{{ $status_code }}\'); return false;">
								<i class="icon-pencil7"></i> Edit
							</button>';
		}
		if($access['delete']==1){
			$delete_action = '<a class="btn btn-link text-danger-600" href="" onclick="del(\''.URL::to('master/road-status-delete/{{ $id }}').'\'); return false;">
								<i class="icon-trash"></i> Hapus
							</a>';
		}

		return Datatables::eloquent($model)
			->addColumn('action', '<div class="text-center">'.
					$update_action.
					$delete_action.
					'<div>')
			->rawColumns(['action'])
			->make(true);

	}
	
	public function add()
	{
		$title = 'Tambah Road Status';
		return view('road.status_add', compact('title'));
	}

	function validateName($status_code) 
    {
        $data = DB::table('TM_ROAD_STATUS')
            ->select('id', 'name as text')
            ->where('status_code', $status_code)
            ->get();

        if (count($data > 0)) {
            return false;
        } else {
            return true;
        }
    }
	
	public function save(RoadStatusRequest $request)
	{
		try {
			RoadStatus::create($request->only('id','status_name','status_code'));
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil menyimpan data');
        return redirect()->route('master.road_status');
	}
	
	public function update(RoadStatusRequest $request)
	{
		try {
				$RS = RoadStatus::find($request->id);
				$RS->status_name = strtoupper($request->status_name);
				$RS->status_code = $request->status_code;
				$RS->updated_by = \Session::get('user_id');
				$RS->save();
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil mengupdate data');
        return redirect()->route('master.road_status');
	}
	
	public function delete($id){
		
		try {
			$data = RoadStatus::find($id);
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
        return redirect()->route('master.road_status');
	}
	
	//STart Master Category Menu
	
	public function category(Request $request)
	{
		$access = access($request);
		// dd($access);
		$title = 'Road Category list';
		$data['ctree'] = '/master/road-category';
		return view('road.category', compact('access','data','title'));
	}
	
	public function category_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'master/road-category');
		$model = VRoadCategory::selectRaw(' @rank  := ifnull(@rank, '.$start.')  + 1  AS no, V_ROAD_CATEGORY.*')->whereRaw('1=1');
		
		$update_action = '';
		$delete_action = '';
		if($access['update']==1){
			$update_action = '
					<button class="btn btn-link text-primary-600" onclick="edit({{ $id }}, \'{{ $category_name }}\', \'{{ $category_code }}\', \'{{ $category_initial }}\', \'{{ $status_id }}\'); return false;">
						<i class="icon-pencil7"></i> Edit
					</button>
			';
		}
		if($access['delete']==1){
			$delete_action = '
					<a class="btn btn-link text-danger-600" href="" onclick="del(\''.URL::to('master/road-category-delete/{{ $id }}').'\'); return false;">
						<i class="icon-trash"></i> Hapus
					</a>
			';
		}
		
		return Datatables::eloquent($model)
			->addColumn('action', '<div class="text-center">
					'.$update_action.'
					'.$delete_action.'
				<div>
				')
			->rawColumns(['action'])
			->make(true);
	}
	
	public function category_save(RoadCategoryRequest $request)
	{
		try {
			RoadCategory::create($request->only('status_id','category_name','category_code','category_initial'));
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil menyimpan data');
        return redirect()->route('master.road_category');
	}
	
	public function category_update(RoadCategoryRequest $request)
	{
		try {
			$RS = RoadCategory::find($request->id);
			$RS->status_id = $request->status_id;
			$RS->category_name = $request->category_name;
			$RS->category_code = $request->category_code;
			$RS->category_initial = $request->category_initial;
			$RS->save();
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil mengupdate data');
        return redirect()->route('master.road_category');
	}
	
	public function category_delete($id){
		
		try {
			$data = RoadCategory::find($id);			
			$data->deleted_at = Carbon::now();
			$data->updated_by = \Session::get('user_id');
			$data->save();
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back();
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back();
		}
		
		\Session::flash('success', 'Berhasil menghapus data');
        return redirect()->route('master.road_category');
	}
	
	
	// STart Master Road Menu
	
	public function road(Request $request)
	{
		$access = access($request);
		$data['ctree'] = '/master/road';
		return view('road.road', compact('access','data'));
	}
	
	public function road_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'master/road');
		
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
		
		$update_action = '';
		$delete_action = '';
		if($access['update']==1){
			$update_action = '
					<button class="btn btn-link text-primary-600" onclick="edit({{ $id }}, \'{{ $total_length }}\', \'{{ $asset_code }}\'); return false;">
						<i class="icon-pencil7"></i> Edit
					</button>
			';
		}
		if($access['delete']==1){
			$delete_action = '
					<a class="btn btn-link text-danger-600" href="" onclick="del(\''.URL::to('master/road-delete/{{ $id }}').'\'); return false;">
						<i class="icon-trash"></i> Hapus
					</a>
			';
		}
		
		return Datatables::eloquent($model)
			->addColumn('action', '<div class="">
					'.$update_action.'
					'.$delete_action.'
				<div>
				')
			->rawColumns(['action'])
			->make(true);
	}
	
	public function road_add(Request $request)
	{
		$data['ctree'] = '/master/road';
		$back = 'master.road';
		return view('road.road_add', compact('data','back'));
	}
	
	public function road_save(RoadRequest $request)
	{
		DB::beginTransaction();
		try {
			$land_use_code = '0601';
			$cat = RoadCategory::find($request->category_id);
			$stat = RoadStatus::find($request->status_id);
			
			$esw 				= explode('-',$request->werks);
			$data['werks'] 		= $esw[0];
			$data['estate_code']= $esw[1];
			
			$bcc = $request->block_code;
			if(in_array( strtoupper($cat->category_name) ,["JALAN AKSES", "JALAN DESA", "JALAN NEGARA"])){ // custom $request->block_code
				
				$getGD = GeneralData::select('description_code','description')->join('TM_COMPANY','TM_COMPANY.company_code','=','TM_GENERAL_DATA.description_code')
								->where('general_code','company_initial')
								->where('TM_COMPANY.company_code',$request->company_code)
								->first();
								
				if(!$getGD){
					throw new \Exception('Company code belum didaftarkan di <a href="'.URL::to('setting/general-data').'">General Data</a>.');
				}
				
				$bcc = '000-'.$getGD->description;
			}
			
			$blck 				= explode('-',$bcc);
			$data['block_code']	= $blck[0];	
			$data['road_code']	= $request->company_code.$esw[1].$blck[0].$land_use_code.$stat->status_code.$cat->category_code.$request->segment;	
			$data['road_name']	= $blck[1].$cat->category_initial.$request->segment;	
			
			
				//cek road_code is exist ?
				$ceki = Road::where('road_code',$data['road_code'])->count();
				if($ceki > 0){
					throw new \Exception('Road code atau segment sudah terdaftar.');
				}
			
			$road 				= Road::create($request->except('segment','werks','status_id','category_id','total_length','asset_code','block_code')+$data);
						
			//insert into TR_ROAD_LOG
			$tr_data = [
				'road_id'		=> $road->id,
				'updated_by'	=> \Session::get('user_id'),
			];
			$ts_data = [
				'road_id'		=> $road->id,
				'updated_by'	=> \Session::get('user_id'),
				'road_code'=>$data['road_code'],
				'road_name'=>$data['road_name']
			];
			RoadLog::create( $request->only('total_length','asset_code')+$tr_data );
			
			//insert into TR_ROAD_STATUS
			
			TRRoadStatus::create( $request->only('status_id','category_id','segment')+$ts_data );
			
		}catch (\Throwable $e) {
			DB::rollBack();
            \Session::flash('error', throwable_msg($e));
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
			DB::rollBack();
            \Session::flash('error', exception_msg($e));
            return redirect()->back()->withInput($request->input());
		}
		
		DB::commit();
		\Session::flash('success', 'Berhasil menyimpan data');
		\Session::flash('hl', 'Highlight');
        return redirect()->route('master.road');
	}
	
	public function road_delete($id){
		
		try {
			$data = Road::find($id);			
			$data->deleted_at = Carbon::now();
			$data->updated_by = \Session::get('user_id');
			$data->save();
		}catch (\Throwable $e) {
            \Session::flash('error', throwable_msg($e));
            return redirect()->back();
        }catch (\Exception $e) {
            \Session::flash('error', exception_msg($e));
            return redirect()->back();
		}
		
		\Session::flash('success', 'Berhasil menghapus data');
        return redirect()->route('master.road');
	}
	
	public function road_update(Request $request)
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
        return redirect()->route('master.road');
	}
	
	public function road_bulk_add()
	{
		$data['ctree'] = '/master/road';
		$back = 'master.road';
		return view('road.road_bulk_add', compact('data','back'));
	}
	
	public function road_bulk_save(Request $request)
	{		
		DB::beginTransaction();
		try {
			$respon['error'] 	= [];
			$respon['success'] 	= [];
			$sheet = $request->data;
			if(count($sheet) > 0){
				foreach($sheet as $k=>$dt){
					
					$land_use_code = '0601';
					$stat = RoadStatus::where('status_code',$dt['status_code'])->first();
					
					if(!$stat){
						$respon['error'][] = ['line'=>($k+1),'status'=>'status code not found'];
						continue;
					}
					
					$cat = RoadCategory::where('category_code',$dt['category_code'])->where('status_id',$stat->id)->first();
					
					if(!$cat){
						$respon['error'][] = ['line'=>($k+1),'status'=>'category code not found'];
						continue;
					}
					$getCom = Company::where('company_code',$dt['company_code'])->first();
					if(!$getCom){
						$respon['error'][] = ['line'=>($k+1),'status'=>'company code not found'];
						continue;
					}
					$getW = Estate::where('werks',$dt['werks'])->where('company_id',$getCom->id)->where('estate_code',$dt['estate_code'])->first();
					if(!$getW){
						$respon['error'][] = ['line'=>($k+1),'status'=>'estate code or plant not found'];
						continue;
					}
					$getAfd = Afdeling::where('company_code',$dt['company_code'])->where('werks',$dt['werks'])->where('afdeling_code',$dt['afdeling_code'])->first();
					if(!$getAfd){
						$respon['error'][] = ['line'=>($k+1),'status'=>'afdeling code not found'];
						continue;
					}
						
					if(in_array( strtoupper($cat->category_name) ,["JALAN AKSES", "JALAN DESA", "JALAN NEGARA"])){ // custom $request->block_code
						
						$getGD = GeneralData::select('description_code','description')->join('TM_COMPANY','TM_COMPANY.company_code','=','TM_GENERAL_DATA.description_code')
										->where('general_code','company_initial')
										->where('TM_COMPANY.company_code',$dt['company_code'])
										->first();
										
						if(!$getGD){
							$respon['error'][] = ['line'=>($k+1),'status'=>'company code not found in general data'];
							continue;
						}
						
						$bcc = '000-'.$getGD->description;
					}else{
						
						$getBlc = Block::where('block_code',$dt['block_code'])->where('werks',$dt['werks'])->where('afdeling_id',$getAfd->id)->first();
						if(!$getBlc){
							$respon['error'][] = ['line'=>($k+1),'status'=>'block code not found'];
							continue;
						}
						$bcc = $getBlc->block_code.'-'.$getBlc->block_name;
					}
					
					$blck 				= explode('-',$bcc);
					$data['block_code']	= $blck[0];	
					$data['road_code']	= $dt['company_code'].$dt['estate_code'].$blck[0].$land_use_code.$stat->status_code.$cat->category_code.$dt['segment'];	
					$data['road_name']	= $blck[1].$cat->category_initial.$dt['segment'];	
					
					
						//cek road_code is exist ?
						$ceki = Road::where('road_code',$data['road_code'])->count();
						if($ceki > 0){
							$respon['error'][] = ['line'=>($k+1),'status'=>'road code or segment is exist'];
							continue;
						}
						
						$str 		= $data['road_code'];
						$min_str 	= -(strlen($str));
						$str_lenks 	= strlen($str)-1;
						$sip 		= substr($str,$min_str,$str_lenks);
						$cko 		= Road::whereRaw(" substring(road_code,$min_str,$str_lenks) = '$sip' ")->count();
						if($cko==9){
							$respon['error'][] = ['line'=>($k+1),'status'=>'Segment is full'];
							continue;
						}
						
					
					$road 				= Road::create($data+Arr::except($dt,['status_code','category_code','segment','total_length','asset_code']));
								
					//insert into TR_ROAD_LOG
					$tr_data = [
						'road_id'		=> $road->id,
						'updated_by'	=> \Session::get('user_id'),
						'total_length'	=> $dt['total_length'],
						'asset_code'	=> @$dt['asset_code'],
					];
					$ts_data = [
						'road_id'		=> $road->id,
						'updated_by'	=> \Session::get('user_id'),
						'road_code'		=> $data['road_code'],
						'road_name'		=> $data['road_name'],
						'status_id'		=> $stat->id,
						'category_id'	=> $cat->id,
						'segment'		=> $dt['segment']
					];
					RoadLog::create( $tr_data );
					
					//insert into TR_ROAD_STATUS
					
					TRRoadStatus::create( $ts_data );
					$respon['success'][] = $k+1;
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
		return response()->success('Success', $respon);
		
		\Session::flash('success', 'Berhasil menyimpan data');
        return redirect()->route('master.road');
	}
}
