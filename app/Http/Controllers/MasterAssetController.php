<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use function GuzzleHttp\json_encode;
use Session;
use API;
use AccessRight;
use App\Workflow;
use App\TR_WORKFLOW_DETAIL;
use App\TR_WORKFLOW_JOB;
use App\TM_GENERAL_DATA;
use App\TM_MSTR_ASSET;
use App\TR_REG_ASSET_DETAIL;

class MasterAssetController extends Controller
{

    public function index()
    {
        //echo "Module Master Asset"; die();
        if (empty(Session::get('authenticated')))
            return redirect('/login');

        if (AccessRight::granted() === false) {
            $data['page_title'] = 'Oops! Unauthorized.';
            return response(view('errors.403')->with(compact('data')), 403);
        }
        
        $access = AccessRight::access();    
        $data['page_title'] = 'Master Asset';
        $data['ctree_mod'] = 'Master Data';
        $data['ctree'] = 'master-asset';
        $data["access"] = (object)$access;
        return view('masterdata.master_asset')->with(compact('data'));
    }

    public function dataGrid(Request $request)
    {
        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";

        $selectedColumn = ['kode_asset_ams','kode_material','nama_material', 'ba_pemilik_asset', 'nama_asset', 'kode_asset_sap'];

        if ($orderColumn) {
            $order = explode("as", $selectedColumn[$orderColumn]);
            if (count($order) > 1) {
                $orderBy = $order[0];
            } else {
                $orderBy = $selectedColumn[$orderColumn];
            }
        }

        $sql = '
            SELECT ' . implode(", ", $selectedColumn) . '
                FROM TM_MSTR_ASSET
                WHERE 1=1
        ';

        if ($request->kode_asset_ams)
        $sql .= " AND kode_asset_ams like'%" . $request->kode_asset_ams . "%'";

        if ($request->kode_material)
        $sql .= " AND kode_material like'%" . $request->kode_material . "%'";

        if ($request->nama_material)
        $sql .= " AND nama_material like'%" . $request->nama_material . "%'";

        if ($request->ba_pemilik_asset)
        $sql .= " AND ba_pemilik_asset like'%" . $request->ba_pemilik_asset . "%'";

        if ($request->nama_asset)
        $sql .= " AND nama_asset like'%" . $request->nama_asset . "%'";

        if ($request->kode_asset_sap)
        $sql .= " AND kode_asset_sap like'%" . $request->kode_asset_sap . "%'";

        if ($orderColumn != "") {
            $sql .= " ORDER BY " . $orderBy . " " . $dirColumn;
        }

        $data = DB::select(DB::raw($sql));

        $iTotalRecords = count($data);
        $iDisplayLength = intval($request->length);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);
        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        for ($i = $iDisplayStart; $i < $end; $i++) {
            $records["data"][] =  $data[$i];
        }

        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return response()->json($records);
    }

    public function store(Request $request)
    {
        try 
        {
            //echo $request->edit_id; die();
            if ($request->edit_id != "") 
            {
                $data = Tm_general_data::find($request->edit_id);
                $sql = " UPDATE TM_GENERAL_DATA SET GENERAL_CODE = '".$request->general_code."', DESCRIPTION_CODE = '".$request->description_code."', DESCRIPTION = '".$request->description."', STATUS = '".$request->status."' WHERE id = ".$request->edit_id." ";
                //echo $sql; die();
                DB::UPDATE($sql);
            } 
            else 
            {
                $data = new Tm_general_data();
                $data->GENERAL_CODE = $request->general_code;
                $data->DESCRIPTION_CODE = $request->description_code;
                $data->DESCRIPTION = $request->description;
                $data->STATUS = $request->status;
                $data->save();
            }
            //echo $request->description_code; die();

            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_id ? 'updated' : 'added')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    /*
    public function show1()
    {
        $param = $_REQUEST;
        //echo "<pre>"; print_r($param);
        $data = Tm_general_data::find($param["id"]);
        return response()->json(array('data' => $data));
    }
    */

    public function show_edit($id)
    {
        $id = base64_decode($id);
        //echo $id; die();
        if (empty(Session::get('authenticated')))
            return redirect('/login');

        $data['page_title'] = 'View - Master Asset';
        $data['ctree_mod'] = 'Master Data';
        $data['ctree'] = 'master-asset';
        $data['id'] = $id;
        $data['content'] = $this->get_master_asset_by_id($id);
        $data['file'] = $this->get_master_asset_file_by_id($id);

        return view('masterdata.master_asset_edit')->with(compact('data'));
    }

    function get_master_asset_file_by_id($id)
    {
        $result = array();

        $sql = " SELECT * FROM TM_MSTR_ASSET_FILE WHERE KODE_ASSET = $id ";
        $data = DB::SELECT($sql);
        //echo "<pre>1"; print_r($data); die();

        /*
        if(!empty($data))
        {
            foreach($data as $k => $v)
            {
                //echo "1<pre>"; print_r($v);
                $result = $v;
            }
        }
        */

        return $data;
    }

    function get_master_asset_by_id($id)
    {
        //echo $id; die();

        $result = array();

        //$sql = " SELECT * FROM TM_MSTR_ASSET WHERE KODE_ASSET_AMS = $id ";
        
        $sql = " SELECT a.*, b.DESCRIPTION AS BA_PEMILIK_ASSET_DESCRIPTION, c.DESCRIPTION AS LOKASI_BA_DESCRIPTION 
                    FROM TM_MSTR_ASSET a 
                        LEFT JOIN TM_GENERAL_DATA b ON a.BA_PEMILIK_ASSET = b.DESCRIPTION_CODE AND b.GENERAL_CODE = 'plant' 
                        LEFT JOIN TM_GENERAL_DATA c ON a.LOKASI_BA_CODE = c.DESCRIPTION_CODE AND c.GENERAL_CODE = 'plant' 
                    WHERE a.KODE_ASSET_AMS = ".$id." ";

        $data = DB::SELECT($sql);

        if(!empty($data))
        {
            foreach($data as $k => $v)
            {
                //echo "1<pre>"; print_r($v);
                $result = $v;
            }
        }

        return $result;

        //echo "1<pre>"; print_r($data); die();
        /*
            Array
            (
                [0] => stdClass Object
                    (
                        [KODE_ASSET_AMS] => 40100137
                        [NO_REG_ITEM] => 2
                        [NO_REG] => 19.07/AMS/PDFA/00034
                        [ITEM_PO] => 1
                        [KODE_MATERIAL] => 000000000402030006
                        [NAMA_MATERIAL] => SEPEDA MOTOR 150CC VERZA HONDA
                        [NO_PO] => 5013106316
                        [BA_PEMILIK_ASSET] => 5141
                        [JENIS_ASSET] => E4010
                        [GROUP] => G22
                        [SUB_GROUP] => SG187
                        [ASSET_CLASS] => 
                        [NAMA_ASSET] => SEPEDA MOTOR 150CC VERZA HONDA
                        [MERK] => verza1
                        [SPESIFIKASI_OR_WARNA] => verza2
                        [NO_RANGKA_OR_NO_SERI] => KC02E1007251
                        [NO_MESIN_OR_IMEI] => MH1KC0213JK007482
                        [NO_POLISI] => 
                        [LOKASI_BA_CODE] => 5141
                        [LOKASI_BA_DESCRIPTION] => 5141-MILL EBL
                        [TAHUN_ASSET] => 2018
                        [KONDISI_ASSET] => B
                        [INFORMASI] => 
                        [NAMA_PENANGGUNG_JAWAB_ASSET] => Joshua
                        [JABATAN_PENANGGUNG_JAWAB_ASSET] => Kerani
                        [ASSET_CONTROLLER] => HT
                        [KODE_ASSET_CONTROLLER] => 
                        [NAMA_ASSET_1] => honda
                        [NAMA_ASSET_2] => verza1
                        [NAMA_ASSET_3] => verza2
                        [QUANTITY_ASSET_SAP] => 1.00
                        [UOM_ASSET_SAP] => un
                        [CAPITALIZED_ON] => 2018-01-01
                        [DEACTIVATION_ON] => 
                        [COST_CENTER] => 5101414701
                        [BOOK_DEPREC_01] => 4
                        [FISCAL_DEPREC_15] => 4
                        [GROUP_DEPREC_30] => 4
                        [DELETED] => 
                        [CREATED_BY] => 25
                        [CREATED_AT] => 2019-07-08 15:02:40
                        [UPDATED_BY] => 
                        [UPDATED_AT] => 
                        [KODE_ASSET_SAP] => 40100137
                        [KODE_ASSET_SUBNO_SAP] => 
                        [GI_NUMBER] => 
                        [GI_YEAR] => 
                    )

            )
        */
    }

    public function inactive(Request $request)
    {
        try {

            $data = Module::find($request->id);
            $data->updated_by = Session::get('user_id');
            $data->deleted = 1;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully inactived']);

        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function active(Request $request)
    {
        try {
            $data = Module::find($request->id);
            $data->updated_by = Session::get('user_id');
            $data->deleted = 0;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully activated']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function select2() {
        $data = DB::table('TBM_MODULE')
        ->select('id', 'name as text')
        ->where('deleted', 0)
        ->get();

        return response()->json(array("data"=>$data));
    }

    public function dataGridDetail(Request $request)
    {
        //echo "<pre>"; print_r($request->id); die();
        $req_id = $request->id;
        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";

        $selectedColumn = ['a.workflow_detail_code','b.workflow_name', 'a.workflow_group_name', 'a.seq', 'a.description'];

        if ($orderColumn) {
            $order = explode("as", $selectedColumn[$orderColumn]);
            if (count($order) > 1) {
                $orderBy = $order[0];
            } else {
                $orderBy = $selectedColumn[$orderColumn];
            }
        }

        $sql = '
            SELECT ' . implode(", ", $selectedColumn) . '
                FROM TR_WORKFLOW_DETAIL a 
                    LEFT JOIN TR_WORKFLOW b ON a.workflow_code = b.workflow_code 
                WHERE a.workflow_code = '.$req_id.'
        ';


        if ($request->workflow_group_name)
        $sql .= " AND a.workflow_group_name like'%" . $request->workflow_group_name . "%'";


        if ($request->description)
        $sql .= " AND a.description like'%" . $request->description . "%'";

        if ($orderColumn != "") {
            $sql .= " ORDER BY " . $orderBy . " " . $dirColumn;
        }

        $data = DB::select(DB::raw($sql));

        $iTotalRecords = count($data);
        $iDisplayLength = intval($request->length);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);
        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        for ($i = $iDisplayStart; $i < $end; $i++) {
            $records["data"][] =  $data[$i];
        }

        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return response()->json($records); # = 1
    }

    public function dataGridDetailJob(Request $request)
    {
        //echo "<pre>"; print_r($request->id); die();
        $req_id = $request->id;
        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";

        $selectedColumn = ['a.workflow_job_code','b.workflow_group_name', 'c.name', 'a.seq', 'a.operation', 'a.lintas'];

        if ($orderColumn) {
            $order = explode("as", $selectedColumn[$orderColumn]);
            if (count($order) > 1) {
                $orderBy = $order[0];
            } else {
                $orderBy = $selectedColumn[$orderColumn];
            }
        }

        $sql = '
            SELECT ' . implode(", ", $selectedColumn) . '
                FROM TR_WORKFLOW_JOB a 
                    LEFT JOIN TR_WORKFLOW_DETAIL b ON a.workflow_detail_code = b.workflow_detail_code
                    LEFT JOIN TBM_ROLE c ON a.id_role = c.id
                WHERE a.workflow_detail_code = '.$req_id.'
        ';

        if ($request->name)
        $sql .= " AND c.name like'%" . $request->name . "%'";


        if ($request->operation)
        $sql .= " AND a.operation like'%" . $request->operation . "%'";

        if ($orderColumn != "") {
            $sql .= " ORDER BY " . $orderBy . " " . $dirColumn;
        }

        $data = DB::select(DB::raw($sql));

        $iTotalRecords = count($data);
        $iDisplayLength = intval($request->length);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);
        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        for ($i = $iDisplayStart; $i < $end; $i++) {
            $records["data"][] =  $data[$i];
        }

        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return response()->json($records); # = 1
    }

    public function workflowcode(){
        $data = DB::table('TR_WORKFLOW')
        ->select('workflow_code as id', 'workflow_name as text')
        //->where('deleted', 0)
        ->get();

        return response()->json(array("data"=>$data));
    }

    public function workflowcodedetail()
    {
        $data = DB::table('TR_WORKFLOW_DETAIL')
        ->select('workflow_detail_code as id', 'workflow_group_name as text')
        //->where('deleted', 0)
        ->get();
        return response()->json(array("data"=>$data));
    }

    public function workflowcoderole()
    {
        $data = DB::table('TBM_ROLE')
        ->select('id', 'name as text')
        //->where('deleted', 0)
        ->get();
        return response()->json(array("data"=>$data));
    }

    public function store_detail(Request $request)
    {
        try 
        {
            if ($request->edit_workflow_code_detail) {
                $data = TR_WORKFLOW_DETAIL::find($request->edit_workflow_code_detail);
                //$data->updated_by = Session::get('user_id');
            } else {
                $data = new TR_WORKFLOW_DETAIL();
                //$data->created_by = Session::get('user_id');
            }

            $data->workflow_code = $request->workflow_code;
            $data->workflow_group_name = $request->workflow_group_name;
            $data->seq = $request->seq;
            $data->description = $request->description;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_workflow_code_detail ? 'updated' : 'added')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function store_detail_job(Request $request)
    {
        try 
        {
            if ($request->edit_workflow_code_detail_job) {
                $data = TR_WORKFLOW_JOB::find($request->edit_workflow_code_detail_job);
                //$data->updated_by = Session::get('user_id');
            } else {
                $data = new TR_WORKFLOW_JOB();
                //$data->created_by = Session::get('user_id');
            }

            $data->workflow_detail_code = $request->workflow_detail_code;
            $data->id_role = $request->id_role;
            $data->seq = $request->seq_job;
            $data->operation = $request->operation;
            $data->lintas = $request->lintas;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_workflow_code_detail_job ? 'updated' : 'added')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    function show_qrcode($amscode)
    {
        
        //include(app_path().'\Providers\qrcode\libs\phpqrcode\phpqrcode.php');
        //echo $amscode;
        //echo url($amscode); die();
        $tempDir = 'public/vendor/QRCode/temp/'; 
        $codeContents = url('master-asset/show-data/$amscode');
        $filename = base64_decode($amscode);
        //$qrcode = new QRcode;
        //QRcode::png($codeContents, $tempDir.''.$filename.'.png', QR_ECLEVEL_L, 5);

        $records = array(
            "tempDir"=>$tempDir,
            "codeContents"=>$codeContents,
            "filename"=>$filename
        );

        echo json_encode($records);
    }

    public function test_qrcode()
    {
        $data["qrcode"] = 'METALLICA'; //QrCode::size(200)->generate('IRVAN TAZRIAN');
        //return view('masterdata.master_asset')->with(compact('data'));
        //echo "test_qrcode"; die();
        return view('masterdata.test_qrcode')->with(compact('data'));
    }

    function print_qrcode($code_ams)
    {
        //echo "5<pre>"; echo $qrcode; die();
        
        $data["code_ams"] = $code_ams;
        $data["data"] = $this->get_data_qrcode($code_ams); 
        return view('masterdata.print_qrcode')->with(compact('data'));
    }

    function get_data_qrcode( $code_ams )
    {   
        $sql = " SELECT a.BA_PEMILIK_ASSET,a.KODE_ASSET_SAP,a.LOKASI_BA_CODE,a.KODE_ASSET_CONTROLLER, b.DESCRIPTION AS BA_PEMILIK_ASSET_DESCRIPTION, a.KODE_ASSET_AMS, c.DESCRIPTION AS LOKASI_BA_DESCRIPTION 
                    FROM TM_MSTR_ASSET a 
                        LEFT JOIN TM_GENERAL_DATA b ON a.BA_PEMILIK_ASSET = b.DESCRIPTION_CODE AND b.GENERAL_CODE = 'plant' 
                        LEFT JOIN TM_GENERAL_DATA c ON a.LOKASI_BA_CODE = c.DESCRIPTION_CODE AND c.GENERAL_CODE = 'plant' 
                    WHERE a.KODE_ASSET_AMS = ".base64_decode($code_ams)." ";

        $data = DB::SELECT($sql);
        return $data;
    }

}
