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

class GeneralDataController extends Controller
{

    public function index()
    {
        //echo "Module General Data Master"; die();
        if (empty(Session::get('authenticated')))
            return redirect('/login');

        if (AccessRight::granted() === false) {
            $data['page_title'] = 'Oops! Unauthorized.';
            return response(view('errors.403')->with(compact('data')), 403);
        }
        
        $access = AccessRight::access();    
        $data['page_title'] = 'Master General Data';
        $data['ctree_mod'] = 'Setting';
        $data['ctree'] = 'setting/general-data';
        $data["access"] = (object)$access;
        return view('usersetting.general_data')->with(compact('data'));
    }

    public function dataGrid(Request $request)
    {
        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";

        $selectedColumn = ['id','general_code','description_code', 'description', 'status'];

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
                FROM TM_GENERAL_DATA
                WHERE 1=1
        ';
		
		$werks = explode(',',session('area_code'));
		$cek =  collect($werks);
		if( $cek->contains('All') ){
			
		}else{
			$ww = '';
			foreach($werks as $k=>$w){
				if($w != 'All'){
					$ww .= $k!=0 ? " ,'$w' " : " '$w' ";
				}
			}
			$sql .= " and (GENERAL_CODE != 'company_initial' or (GENERAL_CODE='company_initial' and DESCRIPTION_CODE in (select company_code from TM_ESTATE a join TM_COMPANY b on b.id = a.company_id where werks in ($ww)))) ";
		}
		
		
        if ($request->general_code)
        $sql .= " AND general_code like'%" . $request->general_code . "%'";


        if ($request->description_code)
        $sql .= " AND description_code like'%" . $request->description_code . "%'";

        if ($request->description)
        $sql .= " AND description like'%" . $request->description . "%'";

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

    public function show()
    {
        $param = $_REQUEST;
        //echo "<pre>"; print_r($param);
        $data = Tm_general_data::find($param["id"]);
        return response()->json(array('data' => $data));
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
}
