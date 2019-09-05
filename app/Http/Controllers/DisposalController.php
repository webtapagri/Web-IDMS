<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Cookie;
use App\TrUser;
use function GuzzleHttp\json_encode;
use Session;
use API;
use AccessRight;
use App\TM_MSTR_ASSET;

class DisposalController extends Controller
{
	
	public function index()
    {	
		if(empty(Session::get('authenticated')))
        return redirect('/login');

		$access = AccessRight::access();
        $data['page_title'] = "Disposal";
        $data['ctree_mod'] = 'Disposal';
        $data['ctree'] = 'disposal-penjualan';

        $data['autocomplete'] = $this->get_autocomplete();
        $data['data'] = $this->get_data_cart(1);
		$data['totalcartnotif'] = array(); //$this->get_totalcartnotif();

        return view('disposal.index')->with(compact('data'));
    }
	
    function get_data_cart($jenis_pengajuan)
	{
		$user_id = Session::get('user_id');

		//$created_by = $u['username'];
		
		$sql = " SELECT * FROM tr_disposal_temp WHERE JENIS_PENGAJUAN = $jenis_pengajuan AND CREATED_BY = $user_id "; //echo $sql; die();
		
		$dt = DB::SELECT($sql);
		//echo "<pre>"; print_r($dt); die();
		
		return $dt;
	}

	function get_autocomplete()
    {   
    	
    	$area_code = Session::get('area_code'); 
    	$where = "";
    	
    	if( $area_code != 'All' )
    	{
    		$where .= " AND BA_PEMILIK_ASSET in ($area_code) ";
    	}

    	$sql = " SELECT a.kode_asset_ams AS kode_asset_ams, a.kode_material AS kode_material, a.nama_material AS nama_material, a.nama_asset_1 AS nama_asset_1, a.kode_asset_sap AS kode_asset_sap 
    				FROM tm_mstr_asset a 
    					WHERE 1=1 $where
    				ORDER BY a.created_at ASC ";
 		$data = DB::SELECT($sql); 

 		if($data)
 		{
 			$datax = '';
 			foreach( $data as $k => $v )
 			{
 				$kode_asset_ams = base64_encode($v->kode_asset_ams);
 				$datax .= "{id : '{$kode_asset_ams}',
 								name : '{$v->nama_material}  ',
 								asset : '{$v->nama_asset_1} '
 							},";
 			}
 		}
 		return rtrim($datax,',');
    }

    function add($id,$jenis_pengajuan)
    {
    	if(empty(Session::get('authenticated')))
        return redirect('/login');

    	//echo $jenis_pengajuan; die();

    	$user_id = Session::get('user_id');
		$kode_asset_ams = base64_decode($id);
		$row = TM_MSTR_ASSET::find($kode_asset_ams);

		$validasi_asset = $this->check_asset($kode_asset_ams,1);
		if( $validasi_asset > 0 )
		{
			Session::flash('alert', 'Data sudah ada (KODE AMS : '.$row->KODE_ASSET_AMS.') ');
			return Redirect::to('/disposal-penjualan');
			exit;
		}
		
		if( $row->count() > 0) 
		{
			//$data = array( 'NO_REG' => $row->NO_REG,'KODE_ASSET_SAP' => $row->kode_asset_sap);
			//$NO_REG = $row->NO_REG;

			DB::beginTransaction();

			try 
			{

				$sql = "INSERT INTO tr_disposal_temp(KODE_ASSET_AMS,KODE_ASSET_SAP,NAMA_MATERIAL,BA_PEMILIK_ASSET,LOKASI_BA_CODE,LOKASI_BA_DESCRIPTION,NAMA_ASSET_1,CREATED_BY,JENIS_PENGAJUAN,CHECKLIST)
							VALUES('{$row->KODE_ASSET_AMS}','{$row->KODE_ASSET_SAP}','{$row->NAMA_MATERIAL}','{$row->BA_PEMILIK_ASSET}','{$row->LOKASI_BA_CODE}','{$row->LOKASI_BA_DESCRIPTION}','{$row->NAMA_ASSET_1}','{$user_id}','{$jenis_pengajuan}',0)";
				//	echo $sql; die();
				DB::insert($sql);
				DB::commit();

				Session::flash('message', 'Success add data to Latest disposal! (KODE AMS : '.$row->KODE_ASSET_AMS.') ');
				return Redirect::to('/disposal-penjualan');
			} 
			catch (\Exception $e) 
			{
				DB::rollback();
				Session::flash('message', $e->getMessage()); 
				return Redirect::to('/disposal-penjualan');
			}
		} 
		else 
		{
			Session::flash('alert-class', 'alert-danger'); 
            return Redirect::to('/disposal-penjualan');
        }
	}

	function remove($kode_asset_ams)
    {	
		
		DB::DELETE(" DELETE FROM tr_disposal_temp WHERE KODE_ASSET_AMS = '{$kode_asset_ams}' ");
		
        //Cart::remove($rowid);
        return Redirect::to('/disposal-penjualan');
    }

    public function index_hilang()
    {
		if(empty(Session::get('authenticated')))
        return redirect('/login');

		$access = AccessRight::access();
        $data['page_title'] = "Disposal";
        $data['ctree_mod'] = 'Disposal';
        $data['ctree'] = 'disposal-hilang';

        $data['autocomplete'] = $this->get_autocomplete();
        $data['data'] = $this->get_data_cart(2);
		$data['totalcartnotif'] = array(); //$this->get_totalcartnotif();

        return view('disposal.index_hilang')->with(compact('data'));
    }

    function add_hilang($id,$jenis_pengajuan)
    {
    	if(empty(Session::get('authenticated')))
        return redirect('/login');

    	//echo $jenis_pengajuan; die();

    	$user_id = Session::get('user_id');
		$kode_asset_ams = base64_decode($id);

		$row = TM_MSTR_ASSET::find($kode_asset_ams);

		$validasi_asset = $this->check_asset($kode_asset_ams,2);
		if( $validasi_asset > 0 )
		{
			Session::flash('alert', 'Data sudah ada (KODE AMS : '.$row->KODE_ASSET_AMS.') ');
			return Redirect::to('/disposal-hilang');
			exit;
		}

		if( $row->count() > 0) 
		{
			//$data = array( 'NO_REG' => $row->NO_REG,'KODE_ASSET_SAP' => $row->kode_asset_sap);
			//$NO_REG = $row->NO_REG;

			DB::beginTransaction();

			try 
			{

				$sql = "INSERT INTO tr_disposal_temp(KODE_ASSET_AMS,KODE_ASSET_SAP,NAMA_MATERIAL,BA_PEMILIK_ASSET,LOKASI_BA_CODE,LOKASI_BA_DESCRIPTION,NAMA_ASSET_1,CREATED_BY,JENIS_PENGAJUAN,CHECKLIST)
							VALUES('{$row->KODE_ASSET_AMS}','{$row->KODE_ASSET_SAP}','{$row->NAMA_MATERIAL}','{$row->BA_PEMILIK_ASSET}','{$row->LOKASI_BA_CODE}','{$row->LOKASI_BA_DESCRIPTION}','{$row->NAMA_ASSET_1}','{$user_id}','{$jenis_pengajuan}',0)";
				//	echo $sql; die();
				DB::insert($sql);
				DB::commit();

				Session::flash('message', 'Success add data to Latest disposal! (KODE AMS : '.$row->KODE_ASSET_AMS.') ');
				return Redirect::to('/disposal-hilang');
			} 
			catch (\Exception $e) 
			{
				DB::rollback();
				Session::flash('message', $e->getMessage()); 
				return Redirect::to('/disposal-hilang');
			}
		} 
		else 
		{
			Session::flash('alert-class', 'alert-danger'); 
            return Redirect::to('/disposal-hilang');
        }
	}

	function remove_hilang($kode_asset_ams)
    {	
		DB::DELETE(" DELETE FROM tr_disposal_temp WHERE KODE_ASSET_AMS = '{$kode_asset_ams}' ");
        return Redirect::to('/disposal-hilang');
    }

    function check_asset($kode_asset_ams,$jenis_pengajuan)
    {
    	$sql = "SELECT COUNT(*) AS TOTAL FROM tr_disposal_temp WHERE KODE_ASSET_AMS = '{$kode_asset_ams}' AND JENIS_PENGAJUAN = $jenis_pengajuan ";
    	$data = DB::SELECT($sql);
    	//echo "2<pre>"; print_r($data); die();
    	return $data[0]->TOTAL;
    }

    public function index_rusak()
    {
		if(empty(Session::get('authenticated')))
        return redirect('/login');

		$access = AccessRight::access();
        $data['page_title'] = "Disposal";
        $data['ctree_mod'] = 'Disposal';
        $data['ctree'] = 'disposal-rusak';

        $data['autocomplete'] = $this->get_autocomplete();
        $data['data'] = $this->get_data_cart(3);
		$data['totalcartnotif'] = array(); //$this->get_totalcartnotif();

        return view('disposal.index_rusak')->with(compact('data'));
    }

	
	function add_rusak($id,$jenis_pengajuan)
    {
    	if(empty(Session::get('authenticated')))
        return redirect('/login');

    	//echo $jenis_pengajuan; die();

    	$user_id = Session::get('user_id');
		$kode_asset_ams = base64_decode($id);

		$row = TM_MSTR_ASSET::find($kode_asset_ams);

		$validasi_asset = $this->check_asset($kode_asset_ams,3);
		if( $validasi_asset > 0 )
		{
			Session::flash('alert', 'Data sudah ada (KODE AMS : '.$row->KODE_ASSET_AMS.') ');
			return Redirect::to('/disposal-rusak');
			exit;
		}

		if( $row->count() > 0) 
		{
			//$data = array( 'NO_REG' => $row->NO_REG,'KODE_ASSET_SAP' => $row->kode_asset_sap);
			//$NO_REG = $row->NO_REG;

			DB::beginTransaction();

			try 
			{

				$sql = "INSERT INTO tr_disposal_temp(KODE_ASSET_AMS,KODE_ASSET_SAP,NAMA_MATERIAL,BA_PEMILIK_ASSET,LOKASI_BA_CODE,LOKASI_BA_DESCRIPTION,NAMA_ASSET_1,CREATED_BY,JENIS_PENGAJUAN,CHECKLIST)
							VALUES('{$row->KODE_ASSET_AMS}','{$row->KODE_ASSET_SAP}','{$row->NAMA_MATERIAL}','{$row->BA_PEMILIK_ASSET}','{$row->LOKASI_BA_CODE}','{$row->LOKASI_BA_DESCRIPTION}','{$row->NAMA_ASSET_1}','{$user_id}','{$jenis_pengajuan}',0)";
				//	echo $sql; die();
				DB::insert($sql);
				DB::commit();

				Session::flash('message', 'Success add data to Latest disposal! (KODE AMS : '.$row->KODE_ASSET_AMS.') ');
				return Redirect::to('/disposal-rusak');
			} 
			catch (\Exception $e) 
			{
				DB::rollback();
				Session::flash('message', $e->getMessage()); 
				return Redirect::to('/disposal-rusak');
			}
		} 
		else 
		{
			Session::flash('alert-class', 'alert-danger'); 
            return Redirect::to('/disposal-rusak');
        }
	}
}

?>