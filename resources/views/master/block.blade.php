@extends('layouts.app')

@section('title', 'Block list')

@section('theme_js')
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>


@endsection

@section('content')

<div class="card">
<?php print_r(Session::get('job_code')); ?>
	<div class="card-header header-elements-inline">
		@if($data['access']->create == 1)
		<button onclick="sync(this)"
			type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left"><b><i class="icon-sync"></i></b> Sync</button>
		@endif
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
				<span class="text-semibold">Berhasil!</span> {{ \Session::get('success') }}
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
				<th>No.</th>
				<th>Block Code</th>
				<th>Block Name</th>
				<th>Region Code</th>
				<th>Company Name</th>
				<th>BA Code</th>
				<th>BA Afdeling Block Code</th>
				<th>Start Valid</th>
				<th>End Valid</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Pencarian</th>
				<th>Block Code</th>
				<th>Block Name</th>
				<th>Region Code</th>
				<th>Company Name</th>
				<th>BA Code</th>
				<th>BA Afdeling Block Code</th>
				<th>Start Valid</th>
				<th>End Valid</th>
			</tr>
		</tfoot>
	</table>
</div>
@endsection

@section('my_script')
<script>
var table

$(document).ready(()=>{
	
	loadGrid();
	
	$('#reloadGrid').click(()=>{
		table.destroy()
		loadGrid()
		console.log(123)
	})
	
	
});

function sync(dis){
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('api/master/sync-block') }}/",
		data: null,
		cache:false,
		beforeSend:function(){
			$(dis).attr('disabled','disabled').html('<b><i class="icon-spinner spinner"></i></b> Please wait...')
		},
		complete:function(){
			$(dis).removeAttr('disabled').html('<b><i class="icon-sync"></i></b> Sync Now')
		},
		headers: {
			"X-CSRF-TOKEN": "{{ csrf_token() }}"
		}
	}).done(function(rsp){
		
		if(rsp.code=200){
			swal({
                title: 'Success!',
                text: 'Success sync! please refresh this page to view newest data',
                type: 'success'
            });
			
			// table.destroy()
			// loadGrid()
		}else{
			alert("Gagal sync");
			console.log(rsp);
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server");
		
	});
}

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
						width: 250,
						targets: [ 4 ]
					},
					{ 
						orderable: false,
						targets: [ 0 ]
					},
					{
						searchable: false,
						targets: [ 0 ]
					},
				],
				dom: '<"datatable-header"Bfrt><"datatable-scroll-wrap"t><"datatable-footer"ip>',
				buttons: [
					   {
							extend: 'csv',
							className: 'btn btn-light d-none toCsv',
							text: '<i class="icon-file-spreadsheet mr-2"></i> CSV',
							extension: '.csv',
						},
						{
							text: 'Download CSV',
							className: 'btn bg-teal-400',
							action: function(e, dt, node, config) {
								
								dt.page.len( -1 ).draw()
								
								donlot = true
															
							}
						}
					],
				"drawCallback": function( settings ) {
					// var api = this.api();
					if(donlot){
						$('.toCsv').click()
						donlot = false
					}
					// console.log( api.rows( {page:'current'} ).data() );
				},
				lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
				language: {
					search: '<span>Filter:</span> _INPUT_',
					searchPlaceholder: 'Type to filter...',
					lengthMenu: '<span>Show:</span> _MENU_',
					paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' },
					processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
				}
			});
	

	table = $('.datatable-responsive').DataTable( {
        processing: true,
        serverSide: true,
        ajax: '{{ route("master.block_datatables") }}',
		// "order": [[1,"asc"],[2, "asc" ]],
        columns: [
            { data: 'no', 	name: 'no' },
            { data: 'block_code', 	name: 'block_code' },
            { data: 'block_name', 	name: 'block_name' },
            { data: 'region_code', 	name: 'region_code' },
            { data: 'company_name', 		name: 'company_name' },
            { data: 'werks', 		name: 'werks' },
            { data: 'werks_afd_block_code', 		name: 'werks_afd_block_code' },
            { data: 'start_valid', 		name: 'start_valid' },
            { data: 'end_valid', 		name: 'end_valid' },
        ],
		initComplete: function () {
			this.api().columns().every(function (k) {
				if(k > 0 && k < 9){
					var column = this;
					var input = document.createElement("input");
					$(input).appendTo($(column.footer()).empty())
					.on('change', function () {
						column.search($(this).val(), false, false, true).draw();
					}).attr('placeholder',' Search').addClass('form-control');
				}
			});
		}
    } );
}
</script>
@endsection