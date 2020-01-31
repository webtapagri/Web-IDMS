<?php

namespace App\DataTables;

// use App\Models\VRoad;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;
use App\Models\VRoad;
use Session;
use AccessRight;
use URL;
use DB;
use Carbon\Carbon;

class RoadDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */

    public function dataTable()
    {
        return datatables()
            ->eloquent($this->query)
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\VRoad $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function query(RoadDataTable $model)


    public function query(Request $request)
    {
        $req = $request->all();
		$start = $req['start'];
		$access = access($request, 'report/road');
		
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
        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('datatable-responsive')
                    ->columns($this->getColumns())
                    ->ajax(route('report.road_datatables'))
                    ->minifiedAjax()
                    ->dom('Blfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('export'),
                        Button::make('print'),
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('estate_name'),
            Column::make('werks'),
            Column::make('afdeling_name'),
            Column::make('block_code'),
            Column::make('block_name'),
            Column::make('status_name'),
            Column::make('category_name'),
            Column::make('segment'),
            Column::make('road_name'),
            Column::make('road_code'),
            Column::make('total_length'),
            Column::make('asset_code'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Road_' . date('YmdHis');
    }
}
