@extends('layouts.app')

@section('title', 'Road Master List')

@section('theme_css')

@endsection

@section('theme_js')
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/extensions/fixed_columns.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/bootbox.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/extensions/jquery_ui/interactions.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/inputs/touchspin.min.js') }}"></script>
<!-- <style>
/* .btexcel {
    margin-right: 20px;
} */
</style> -->

@endsection

@section('content')

<div class="card">
	<div class="card-header header-elements-inline">
		
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" id="reloadGrid" data-action="reload"></a>


			</div>
		</div>
	</div>

	<div class="card-body">
		@if (\Session::has('success'))
			<div class="alert alert-success no-border">
				<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
				<span class="text-semibold">Success!</span> {{ \Session::get('success') }}
			</div>
		@endif
		
		@if (\Session::has('error'))
			<div class="alert alert-warning no-border">
				<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
				<span class="text-semibold">Error!</span> {{ \Session::get('error') }}
			</div>
		@endif
		
		@if ($errors->any())
			<div class="alert alert-danger no-border">
				Terdapat error:
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
	</div>
    
	<table class="table datatable-responsive">
		<thead>
			<tr>
				<th>Company</th>
				<th>Estate</th>
				<th>Afdeling</th>
				<th>Block</th>
				<th>Status</th>
				<th>Category</th>
				<th>Segment</th> 
				<th>BA Code</th>
				<th>Road Name</th>
				<th>Road Code</th>
				<th>Length</th>
				<th>Asset Code</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Company</th>
				<th>Estate</th>
				<th>Afdeling</th>
				<th>Block</th>
				<th>Status</th>
				<th>Category</th>
				<th>Segment</th> 
				<th>BA Code</th>
				<th>Road Name</th>
				<th>Road Code</th>
				<th>Length</th>
				<th>Asset Code</th>
			</tr>
		</tfoot>
	</table>
</div>

<form action="{{ route('report.download_road') }}" id="formDownload" method="post">
	@csrf
	<input type="hidden" name="que" id="que">
	<input type="hidden" name="que_global" id="que_global">
</form>

@endsection

@section('my_script')
<script>
var table, col, que

$(document).ready(()=>{
	
	
	loadGrid();
	
	$('#reloadGrid').click(()=>{
		table.destroy()
		loadGrid()
		console.log(123)
	})
	
	// formRequiredMark()
	
});

function loadGrid(){
	var donlot = false

	//------- cache client --------
	$.fn.dataTable.pipeline = function ( opts ) {
		// Configuration options
		var conf = $.extend( {
			pages: 5,     // number of pages to cache
			url: '',      // script url
			data: null,   // function or object with parameters to send to the server
						  // matching how `ajax.data` works in DataTables
			method: 'GET' // Ajax HTTP method
		}, opts );
	 
		// Private variables for storing the cache
		var cacheLower = -1;
		var cacheUpper = null;
		var cacheLastRequest = null;
		var cacheLastJson = null;
	 
		return function ( request, drawCallback, settings ) {
			var ajax          = false;
			var requestStart  = request.start;
			var drawStart     = request.start;
			var requestLength = request.length;
			var requestEnd    = requestStart + requestLength;
			 
			if ( settings.clearCache ) {
				// API requested that the cache be cleared
				ajax = true;
				settings.clearCache = false;
			}
			else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
				// outside cached data - need to make a request
				ajax = true;
			}
			else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
					  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
					  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
			) {
				// properties changed (ordering, columns, searching)
				ajax = true;
			}
			 
			// Store the request for checking next time around
			cacheLastRequest = $.extend( true, {}, request );
	 
			if ( ajax ) {
				// Need data from the server
				if ( requestStart < cacheLower ) {
					requestStart = requestStart - (requestLength*(conf.pages-1));
	 
					if ( requestStart < 0 ) {
						requestStart = 0;
					}
				}
				 
				cacheLower = requestStart;
				cacheUpper = requestStart + (requestLength * conf.pages);
	 
				request.start = requestStart;
				request.length = requestLength*conf.pages;
	 
				// Provide the same `data` options as DataTables.
				if ( typeof conf.data === 'function' ) {
					// As a function it is executed with the data object as an arg
					// for manipulation. If an object is returned, it is used as the
					// data object to submit
					var d = conf.data( request );
					if ( d ) {
						$.extend( request, d );
					}
				}
				else if ( $.isPlainObject( conf.data ) ) {
					// As an object, the data given extends the default
					$.extend( request, conf.data );
				}
	 
				settings.jqXHR = $.ajax( {
					"type":     conf.method,
					"url":      conf.url,
					"data":     request,
					"dataType": "json",
					"cache":    false,
					"success":  function ( json ) {
						cacheLastJson = $.extend(true, {}, json);
	 
						if ( cacheLower != drawStart ) {
							json.data.splice( 0, drawStart-cacheLower );
						}
						if ( requestLength >= -1 ) {
							json.data.splice( requestLength, json.data.length );
						}
						 
						drawCallback( json );
					}
				} );
			}
			else {
				json = $.extend( true, {}, cacheLastJson );
				json.draw = request.draw; // Update the echo for each response
				json.data.splice( 0, requestStart-cacheLower );
				json.data.splice( requestLength, json.data.length );
	 
				drawCallback(json);
			}
		}
	};
	 
	// Register an API method that will empty the pipelined data, forcing an Ajax
	// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
	$.fn.dataTable.Api.register( 'clearPipeline()', function () {
		return this.iterator( 'table', function ( settings ) {
			settings.clearCache = true;
		} );
	} );

	//------- cache client --------

	$.extend( $.fn.dataTable.defaults, {
				autoWidth: false,
				responsive: true,
				columnDefs: [
					{ 
						orderable: false,
						width: 150,
						targets: [ 0 ]
					},
					{ 
						orderable: false,
						width: 150,
						targets: [ 1 ]
					},
					{ 
						orderable: false,
						width: 150,
						targets: [ 7 ]
					},
					{
						searchable: false,
						targets: [ 0 ]
					},
				],
				dom: '<"datatable-header"Bfl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
				buttons: [
					   {
							extend: 'csv',
							className: 'btn btn-light d-none toCsv',
							text: '<i class="icon-file-spreadsheet mr-2"></i> CSV',
							extension: '.csv',
						},
						{
							text: '<i class="icon-file-excel mr-2"></i>Export to EXCEL',
							className: 'btn bg-teal-400',
							action: function(e, dt, node, config) {
								
								// dt.page.len( -1 ).draw()
								// donlot = true

								que = [];
								que_global = $('input[type="search"]').val()
								var tbll = $('.datatable-responsive').find('.tfsearch').length / 2
								$('.datatable-responsive').find('.tfsearch').each((k,v)=>{
									if( $(v).val() != '' ){
										que.push( { col: col[k]['name'], val : $(v).val()} )
									}    
								})
								
								$('#que').val( JSON.stringify(que) )
								$('#que_global').val( que_global )
								$('#formDownload').submit()
									
															
							}
						}
					],
				"drawCallback": function( settings ) {
					// var api = this.api();
					if(donlot){
						$('.toCsv').click()
						donlot = false
					}
				},
			lengthMenu: [
				[ 10, 25, 50, -1 ],
				[ '10', '25', '50', 'All' ]
			],
				language: {
					search: '<span>Filter:</span> _INPUT_',
					searchPlaceholder: 'Type to filter...',
					lengthMenu: '<span>Show:</span> _MENU_',
					paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
				}
			});
			
	col = [		
		{ data: 'company_name', 	name: 'company_name' },
		{ data: 'estate_name', 		name: 'estate_name' },
		{ data: 'afdeling_code', 	name: 'afdeling_code' },
		{ data: 'block_code', 		name: 'block_code' },
		{ data: 'status_name', 		name: 'status_name' },
		{ data: 'category_name', 	name: 'category_name' },
		{ data: 'segment',      	name: 'segment' },
		{ data: 'werks', 	    	name: 'werks' },
		{ data: 'road_name', 		name: 'road_name' },
		{ data: 'road_code', 		name: 'road_code' },
		{ data: 'total_length', 	name: 'total_length' },
		{ data: 'asset_code', 		name: 'asset_code' },
	];

	table = $('.datatable-responsive').DataTable( {
        processing: true,
		serverSide: true,
        ajax: '{{ route("report.road_datatables") }}',
		// "order": [[1,"asc"],[2, "asc" ]], 
        columns: col,
			
		initComplete: function () {
			this.api().columns().every(function (k) {
				if(k > -1 && k < 12){
					if(k == 4){
						var column = this;
						var dStatus = '<option value="PRODUKSI">PRODUKSI</option><option value="NON PRODUKSI">NON PRODUKSI</option><option value="UMUM">UMUM</option>';
						var select = $('<select class="form-control"><option value="">'+dStatus+'</option></select>')
							.appendTo( $(column.footer()).empty() )
							.on( 'change', function () {
								var val = $.fn.dataTable.util.escapeRegex(
									$(this).val()
								);
		 
								column
									.search( val ? '^'+val+'$' : '', true, false )
									.draw();
							} );
						
					}else{
						var column = this;
						var input = document.createElement("input");
						$(input).appendTo($(column.footer()).empty())
						.on('change', function () {
							column.search($(this).val(), false, false, true).draw();
						}).attr('placeholder',' Search').addClass('form-control tfsearch');
					}
				}
			});
		}
	} );

	
	// 'use strict';
	// yadcf.init(table,
    //     [
    //         {
    //             column_number : 7,
	// 			filter_type: "multi_select",
	// 			select_type: 'select2',
	// 		}
    //     ],
    //     {
    //         cumulative_filtering: true
    //     }
	// );

	
}
</script>
@endsection