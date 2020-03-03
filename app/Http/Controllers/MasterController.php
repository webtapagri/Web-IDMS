<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Master;
use App\Models\Company;
use App\Models\Estate;
use App\Models\Afdeling;
use App\Models\Block;
use App\Models\VEstate;
use App\Models\VBlock;
use App\Models\VAfdeling;
use AccessRight;
use Yajra\DataTables\Facades\DataTables;
use DB;
use API;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Jobs\FlushCache;

class MasterController extends Controller
{
    
	/*
		Endpoint:
		
			afdeling/all
			block/all
			comp/all
			est/all
			region/all
	
	*/

	
	public function sync_afd()
	{
		$Master = new Master;
		$token = $Master->token();
		$RestAPI = $Master
					->setEndpoint('afdeling')
					// ->setEndpoint('est/all')
					->setHeaders([
						'Authorization' => 'Bearer '.$token
					])
					->get();
		
		if(isset($RestAPI['http_status_code'])){
			if($RestAPI['http_status_code'] == 200){
				$results = $RestAPI['data']['results'];
				$jml = count($results);
				if($jml > 0){
					foreach($results as $data){
						$est = Estate::where('werks',$data['WERKS'])->first();
						
						if($est){
							try {
								$afd = Afdeling::firstOrNew(array('estate_id' => $est['id'],'afdeling_code' => $data['afd_code']));
								$afd->region_code = $data['region_code'];
								$afd->company_code = $data['comp_code'];
								$afd->afdeling_name = $data['afd_name'];
								$afd->werks = $data['werks'];
								// $afd->werks_afd_code = $data['WERKS_AFD_CODE'];
								$afd->werks_afd_code = $data['werks'].$data['afd_code'];
								$afd->save();
							}catch (\Throwable $e) {
								return response()->error('Error', 'Terjadi kesalahan server / API');
							}catch (\Exception $e) {
								return response()->error('Error', 'Terjadi kesalahan server / API');
							}
						}else{
							// masuk log  COMP_CODE  not found
						}
						
					}
						
				}else{
					return response()->error('Success', 'API tidak memberikan data');
				}
			}else{
				return response()->error('Success', "Terjadi error sync master {$RestAPI['http_status_code']} ");
			}	
		}else{
			return response()->error('Success', 'Terjadi kesalahan server / API');
		}	
		
		dispatch((new FlushCache)->onQueue('low'));	
		return response()->success('Success', $jml);
		
	}
	

	
	public function sync_block($comp, $est)
	{
		$comp_code = $comp;
		$est_code = $est;
		// $Master = new Master;
		// $token = $Master->token();
		if($est_code == 0){
			$param = $comp_code;
		}else{
			$param = $est_code;
		}

		$Master = new Master;
		$token = $Master->token();
		$RestAPI = $Master
					->setEndpoint('block/'.$param)
					// ->setEndpoint('est/all')
					->setHeaders([
						'Authorization' => 'Bearer '.$token
					])
					->get();
		
		if(isset($RestAPI['http_status_code'])){
			if($RestAPI['http_status_code'] == 200){
				$results = $RestAPI['data']['results'];
				$jml = count($results);
				if($jml > 0){
					foreach($results as $data){
						$afd = Afdeling::where('afdeling_code',$data['afd_code'])->where('werks',$data['werks'])->first();
						
						if($afd){
							try {
								$block = Block::firstOrNew(array(
									'afdeling_id' => $afd['id'],
									'block_code' => $data['block_code'],
									'start_valid' => $data['start_valid'],									
									// 'end_valid' => $data['END_VALID'],									
								));
								$block->block_name = $data['block_name'];
								$block->region_code = $data['region_code'];
								$block->company_code = $data['comp_code'];
								$block->estate_code = $data['est_code'];
								$block->werks = $data['werks'];
								// $block->werks_afd_block_code = $data['WERKS_AFD_BLOCK_CODE'];
								$block->werks_afd_block_code = $data['werks'].$data['afd_code'].$data['block_code'];
								// $block->latitude_block = $data['LATITUDE_BLOCK'];
								// $block->longitude_block = $data['LONGITUDE_BLOCK'];
								$block->latitude_block = '';
								$block->longitude_block = '';
								$block->start_valid = date("Y-m-d", strtotime($data['start_valid']));
								$block->end_valid = date("Y-m-d", strtotime($data['end_valid']));
								$block->save();
						}catch (\Throwable $e) {
								return response()->error('Error', 'Terjadi kesalahan server / API');
							}catch (\Exception $e) {
								return response()->error('Error', 'Terjadi kesalahan server / API');
							}
						}else{
							// masuk log  COMP_CODE  not found
						}
						
					}
						
				}else{
					return response()->error('Success', 'API tidak memberikan data');
				}
			}else{
				return response()->error('Success', "Terjadi error sync master {$RestAPI['http_status_code']} ");
			}	
		}else{
			return response()->error('Success', 'Terjadi kesalahan server / API');
		}	
		
		dispatch((new FlushCache)->onQueue('low'));	
		return response()->success('Success', $jml);
		
	}
	


	public function sync_comp()
	{
		$Master = new Master;
		$token = $Master->token();
		$RestAPI = $Master
					// ->setEndpoint('comp/all')
					->setEndpoint('company')
					->setHeaders([
						// 'Authorization' => 'Bearer '.$token
					])
					->get();
		if(isset($RestAPI['http_status_code'])){
			if($RestAPI['http_status_code'] == 200){
				$results = $RestAPI['data']['results'];
				$jml 	 = count($results);
				if($jml > 0 ){
					foreach($results as $data){
						try {
							$comp = Company::firstOrNew(array(
									'region_code' => $data['region_code'],
									'company_code' => $data['comp_code']
								));
							$comp->company_name = $data['comp_name'];
							$comp->address = $data['address'];
							$comp->national = $data['national'];
							$comp->insert_time_dw = $data['insert_time_dw'];
							$comp->update_time_dw = $data['update_time_dw'];
							$comp->save();
						}catch (\Throwable $e) {
							return response()->error('Error', 'Terjadi kesalahan server / API');
						}catch (\Exception $e) {
							return response()->error('Error', 'Terjadi kesalahan server / API');
						}
					}
						
				}else{
					return response()->error('Success', 'API tidak memberikan data');
				}
					
			}else{
				return response()->error('Success', "Terjadi error sync master {$RestAPI['http_status_code']} ");
			}
				
		}else{
			return response()->error('Error', 'Terjadi kesalahan server / API');
		}			
		
		dispatch((new FlushCache)->onQueue('low'));			
		return response()->success('Success', $jml);
	}
	
	public function sync_est()
	{
		$Master = new Master;
		$token = $Master->token();
		$RestAPI = $Master
					->setEndpoint('estate')
					// ->setEndpoint('est/all')
					->setHeaders([
						'Authorization' => 'Bearer '.$token
					])
					->get();
		
		if(isset($RestAPI['http_status_code'])){
			if($RestAPI['http_status_code'] == 200){
				$results = $RestAPI['data']['results'];
				$jml = count($results);
				if($jml > 0){
					foreach($results as $data){
						$comp = Company::where('company_code',$data['comp_code'])->first();
						
						if($comp){
							try {
								$est = Estate::firstOrNew(array(
											'company_id' 	=> $comp['id'],
											'estate_code' 	=> $data['est_code'],
											'werks' 		=> $data['werks'],
											'start_valid' 	=> $data['start_valid'],
										));
								$est->estate_name 		= $data['est_name'];
								$est->werks 			= $data['werks'];
								$est->city 				= $data['city'];
								$comp->start_valid 		= $data['start_valid'];
								$comp->end_valid 		= $data['end_valid'];
								$est->region_code 		= $data['region_code'];
								$est->insert_time_dw 	= $data['insert_time_dw'];
								$est->update_time_dw 	= $data['update_time_dw'];
								$est->save();
							}catch (\Throwable $e) {
								return response()->error('Error', 'Terjadi kesalahan server / API');
							}catch (\Exception $e) {
								return response()->error('Error', 'Terjadi kesalahan server / API');
							}
						}else{
							// masuk log  COMP_CODE  not found
						}
						
					}
						
				}else{
					return response()->error('Success', 'API tidak memberikan data');
				}
			}else{
				return response()->error('Success', "Terjadi error sync master {$RestAPI['http_status_code']} ");
			}	
		}else{
			return response()->error('Success', 'Terjadi kesalahan server / API');
		}	
		
		dispatch((new FlushCache)->onQueue('low'));	
		return response()->success('Success', $jml);
		
	}
	
	public function company()
	{
		$access = AccessRight::roleaccess();
		$title = 'Master Data Company';
		$data['ctree'] = '/master/company';
		$data["access"] = (object)$access['access'];
		return view('master.company', compact('data','title'));
	}
	
	public function company_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'master/company');
		
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
			$where = "id in (select distinct company_id from TM_ESTATE where werks in ($ww))";
		}
		// dd($start);
		// $model = Company::selectRaw(' @rank  := ifnull(@rank, '.$start.')  + 1  AS no, TM_COMPANY.*')->whereRaw($where);
		$model = DB::select( DB::raw('select @rank  := ifnull(@rank, '.$start.')  + 1  AS no, TM_COMPANY.* from TM_COMPANY where '.$where));
		
		$collection = collect($model);
		// return Datatables::eloquent($model)
		return Datatables::of($collection)
			->rawColumns(['action'])
			->make(true);
	}
	
	public function estate()
	{
		$access = AccessRight::roleaccess();
		$title = 'Master Data Estate';
		$data['ctree'] = '/master/estate';
		$data["access"] = (object)$access['access'];
		return view('master.estate', compact('data','title'));
	}
	
	public function estate_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'master/estate');
		
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
		
		$model = VEstate::selectRaw(' @rank  := ifnull(@rank, '.$start.')  + 1  AS no, V_ESTATE.*')->whereRaw($where);		
		
		return Datatables::eloquent($model)
			->rawColumns(['action'])
			->make(true);
	}
	
	public function afdeling()
	{
		$access = AccessRight::roleaccess();
		$title = 'Master Data Afdeling';
		$data['ctree'] = '/master/afdeling';
		$data["access"] = (object)$access['access'];
		return view('master.afdeling', compact('data','title'));
	}
	
	public function afdeling_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'master/afdeling');
		
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
		
		$model = VAfdeling::selectRaw(' @rank  := ifnull(@rank, '.$start.')  + 1  AS no, V_AFDELING.*')->whereRaw($where);		
		
		return Datatables::eloquent($model)
			->rawColumns(['action'])
			->make(true);
	}

	
	public function block()
	{
		$access = AccessRight::roleaccess();
		$title = 'Master Data Block';
		$data['ctree'] = '/master/block';
		$data["access"] = (object)$access['access'];
		return view('master.block', compact('data','title'));
	}
	
	public function block_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'master/block');
		
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
		
		$model = VBlock::selectRaw(' @rank  := ifnull(@rank, '.$start.')  + 1  AS no, V_BLOCK.*')->whereRaw($where);	
		
		return Datatables::eloquent($model)
			->rawColumns(['action'])
			->make(true);
	}
	
	public function api_company()
	{
		try{
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
				$where = "id in (select distinct company_id from TM_ESTATE where werks in ($ww))";
			}
			$data = Company::selectRaw('id, company_code, company_name')->whereRaw($where)->get();
			
		}catch (\Throwable $e) {
            return response()->error('Error',throwable_msg($e));
        }catch (\Exception $e) {
            return response()->error('Error',exception_msg($e));
		}
		return response()->success('Success', $data);
	}
	
	public function api_estate_tree($id)
	{
		try{
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
				$where = "TM_ESTATE.werks in ($ww)";
			}
			$data = Estate::
							selectRaw('estate_code, werks, estate_name')
							->join('TM_COMPANY','TM_COMPANY.id','=','TM_ESTATE.company_id')
							->where('TM_COMPANY.company_code',$id)
							->whereRaw($where)
							->get();
			
		}catch (\Throwable $e) {
            return response()->error('Error',throwable_msg($e));
        }catch (\Exception $e) {
            return response()->error('Error',exception_msg($e));
		}
		return response()->success('Success', $data);
	}
	
	public function api_afdeling_tree($id)
	{
		try{
			$d = explode('-',$id);
			$data = Afdeling::
							selectRaw('afdeling_code, afdeling_name')
							->where('werks',$d[0])
							->get();
			
		}catch (\Throwable $e) {
            return response()->error('Error',throwable_msg($e));
        }catch (\Exception $e) {
            return response()->error('Error',exception_msg($e));
		}
		return response()->success('Success', $data);
	}
	
	public function api_block_tree($id, $werks)
	{
		try{
			$d = explode('-',$werks);
			$data = Block::
							selectRaw('block_code, block_name')
							->whereRaw("substring(werks_afd_block_code,5,1) = '$id' and werks = '{$d[0]}' and now() and start_valid <= now() and end_valid >= now()")
							->get();
			
		}catch (\Throwable $e) {
            return response()->error('Error',throwable_msg($e));
        }catch (\Exception $e) {
            return response()->error('Error',exception_msg($e));
		}
		return response()->success('Success', $data);
	}
	
	public function api_estate()
	{
		$data = DB::table('TM_ESTATE')
        ->select('werks as id', 'estate_name as text')
        ->get();

        $arr = array();
		$arr[] = ['id'=>'All','text'=>'All-All Business Area Code'];
        foreach ($data as $row) {
            $arr[] = array(
                "id" => $row->id,
                "text" => $row->id .'-' . $row->text
            );
        }

        return response()->json(array('data' => $arr));
	}
	
	public function cace()
	{
		// $p = Redis::incr('p');
		// return $p;
		
		$value = Cache::remember('company', 1/2, function () {
			return Company::all();
		});
		return $value;
	}
	public function caces()
	{
		if (Cache::has('company')){
			$value = Cache::get('company');
		} else {
			$value=  0;
		}
		return $value;
	}
	
}
