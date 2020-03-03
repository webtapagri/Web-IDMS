@extends('layouts.app')

@section('title', 'Block list')

@section('theme_js')
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/bootbox.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>


@endsection

<style>
#modal_info {
  width: 50%;
  margin: auto;
}
</style>


@section('content')

<div class="card">
<?php print_r(Session::get('job_code')); ?>
	<div class="card-header header-elements-inline">
		@if($data['access']->create == 1)
		<button  data-toggle="modal" data-target="#modal_info"
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
				<!-- <th>No.</th> -->
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
		
	</table>
</div>



<div id="modal_info" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Sync Option</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			
			
			<!-- <form action="{{ route('history.progres_perkerasan_update') }}" method="post" class="form-horizontal f-detail needs-validation" novalidate> -->
			<form onsubmit="sync(this)" method="get" class="form-horizontal f-detail needs-validation">
				@csrf
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Company</label>
						<div class="col-sm-9">
							<select required="" data-placeholder="Select Company" name="company_code" id="company_code"  class="form-control company_code">
								<option value=""></option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Estate</label>
						<div class="col-sm-9">
							<select data-placeholder="Select Estate" name="estate_code" id="estate_code"  class="form-control estate_code">
								<option value=""></option>
							</select>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
					<button type="submit"   class="btn btn-primary btn-ladda btn-ladda-spinner ladda-button legitRipple btn-f-detail" data-style="expand-left" data-spinner-color="#333" data-spinner-size="20">
						<span class="ladda-label">Sync</span>
						<span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div>
					</button>
				</div>
			</form>
		</div>
	</div>
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


$('#modal_info').on('show.bs.modal', function () {
	load_comp();
		
	$('.company_code').change(()=>{
		var id = $('.company_code').val()
		load_est(id)
	})
	// $('.savedata').click(()=>{
	// 		sync(dis)
	// 		$('#modal_info').modal('hide')
	// })
})


function load_comp(x=null){
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('api/master/company') }}/",
		data: null,
		cache:false,
		beforeSend:function(){
			$('.company_code').html('<option value=""></option>')
		},
		complete:function(){
			
		},
		headers: {
			"X-CSRF-TOKEN": "{{ csrf_token() }}"
		}
	}).done(function(rsp){
		
		if(rsp.code=200){
			var cont = rsp.contents
			$.each(cont, (k,v)=>{
				if(x==v.company_code){
					$('.company_code').append('<option selected value="'+v.company_code+'">'+v.company_code+' - '+v.company_name+'</option>')
				}else{
					$('.company_code').append('<option value="'+v.company_code+'">'+v.company_code+' - '+v.company_name+'</option>')
				}
				
			})
		}else{
			$('.company_code').html(rsp.code+' - '+rsp.contents)
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server");
		
	});
}


function load_est(id, x=null){
	$.ajax({
		param:{
			elTarget:'.estate_code',
			elValue:'<option value=""></option>',
		},
		type: 'GET',
		url: "{{ URL::to('api/master/estate_tree/') }}/"+id,
		data: null,
		cache:true,
		beforeSend:function(){
			$('.estate_code').html('<option value=""></option>')
			HoldOn(light)
		},
		complete:function(){
			HoldOff(light)
		},
		headers: {
			"X-CSRF-TOKEN": "{{ csrf_token() }}"
		},
		success:(rsp)=>{
			if(rsp.code=200){
				var cont = rsp.contents
				$.each(cont, (k,v)=>{
					
					if(x == v.werks+'-'+v.estate_code){
						$('.estate_code').append('<option selected value="'+v.werks+'">'+v.werks+' - '+v.estate_name+'</option>')
					}else{
						$('.estate_code').append('<option value="'+v.werks+'">'+v.werks+' - '+v.estate_name+'</option>')
					}
				})
			}else{
				$('.estate_code').html(rsp.code+' - '+rsp.contents)
			}
		},
		error:(xhr, ajaxOptions, thrownError)=>{
			swal({
				title: xhr.status.toString(),
				text: 'Oops.. '+thrownError,
				type: 'error',
				padding: 30
			});
		}
	});
}


function sync(dis){
	var company = $('.company_code').val()
	var estate = ($('.estate_code').val())
	var est = 0;
	if(estate == ''){
		est = 0;
	}else{
		est = estate
	}
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('api/master/sync-block') }}"+"/"+ + company + "/" + est,
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
	$('#modal_info').modal('hide')
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
	
	$('.datatable-responsive thead tr').clone(true).appendTo( '.datatable-responsive thead' );
    $('.datatable-responsive thead tr:eq(1) th').each( function (i) {
		var title = $(this).text();
		if(title !="Action"){
			$(this).html( '<input type="text" class ="form-control tfsearch" placeholder="Search" />' );
			
	
			$( 'input', this ).on( 'click change', function (event) {
					if ( table.column(i).search() !== this.value ) {
						
							table
								.column(i)
								.search( this.value )
								.draw();
							
					}
			} );
		}
		else{
			$(this).html( '' );
		}
	} );

	$.extend( $.fn.dataTable.defaults, {
				autoWidth: false,
				responsive: false,
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
				dom: '<"datatable-header"Blr><"datatable-scroll-wrap"t><"datatable-footer"ip>',
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
		orderCellsTop: true,
		scrollX: true,
		scrollY: '350px',
        ajax: '{{ route("master.block_datatables") }}',
		// "order": [[1,"asc"],[2, "asc" ]],
        columns: [
            // { data: 'no', 	name: 'no' },
            { data: 'block_code', 	name: 'block_code' },
            { data: 'block_name', 	name: 'block_name' },
            { data: 'region_code', 	name: 'region_code' },
            { data: 'company_name', 		name: 'company_name' },
            { data: 'werks', 		name: 'werks' },
            { data: 'werks_afd_block_code', 		name: 'werks_afd_block_code' },
            { data: 'start_valid', 		name: 'start_valid' },
            { data: 'end_valid', 		name: 'end_valid' },
        ],
		
    } );
}
</script>
@endsection