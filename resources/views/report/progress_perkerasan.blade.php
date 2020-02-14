@extends('layouts.app')

@section('title', 'Report Progress Perkerasan Jalan')

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
	<table class="table datatable-responsive table-xs">
		<thead>
			<tr>
				<th>Company</th>
				<th>Estate</th>
				<th>Afdeling</th>
				<th>Block</th>
				<th>Month</th>
				<th>Year</th>
				<th>Status</th>
				<th>Category</th>
				<th>Segment</th>
				<th>Road Name</th>
				<th>Road Code</th>
				<th>Length</th>
				<th>Pavement Length (m)</th>
				<th>Asset Code</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Company</th>
				<th>Estate</th>
				<th>Afdeling</th>
				<th>Block</th>
				<th>Month</th>
				<th>Year</th>
				<th>Status</th>
				<th>Category</th>
				<th>Segment</th>
				<th>Road Name</th>
				<th>Road Code</th>
				<th>Length</th>
				<th>Pavement Length (m)</th>
				<th>Asset Code</th>
			</tr>
		</tfoot>
	</table>
</div>

<div id="modal_edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Update Perkerasan Jalan</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<form action="{{ route('history.progres_perkerasan_update') }}" method="post" class="form-horizontal">
				@csrf
				<input type="hidden" id="rc_id" name="road_id">
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Panjang Perkerasan (m)</label>
						<div class="col-sm-9 tsp">
							
						</div>
					</div>
					<?php 
					$start = $month = strtotime('2009-01-01');
					$end = strtotime('2010-01-01');
					?>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Bulan</label>
						<div class="col-sm-9">
							<select required data-placeholder="Select Month" name="month"  class="form-control month">
								<option value=""></option>
								<?php while($month < $end){ ?>
									<option value="{{ date('m', $month) }}">{{ date('F', $month) }}</option>
								<?php
										$month = strtotime("+1 month", $month);	 
									}
								?>
							</select>
						</div>
					</div>
					<?php 
					$st = $mon = strtotime('2009-01-01');
					$en = strtotime('2031-01-01');
					?>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Tahun</label>
						<div class="col-sm-9">
							<select required data-placeholder="Select Year" name="year"  class="form-control year">
								<option value=""></option>
								<?php while($mon < $en){ ?>
									<option value="{{ date('Y', $mon) }}">{{ date('Y', $mon) }}</option>
								<?php
										$mon = strtotime("+1 year", $mon);	 
									}
								?>
							</select>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner ladda-button legitRipple" data-style="expand-left" data-spinner-color="#333" data-spinner-size="20">
						<span class="ladda-label">SImpan</span>
						<span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="modal_detail" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Progress Perkerasan Jalan</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				Kode Jalan: <strong id="detailKodeJalan"></strong>
			</div>
			<table class="table datatable-detail table-xs">
				<thead>
					<tr>
						<th>Panjang (m)</th>
						<th>Bulan</th>
						<th>Tahun</th>
						<th>Oleh</th>
						<th>Insert Date</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Panjang (m)</th>
						<th>Bulan</th>
						<th>Tahun</th>
						<th>Oleh</th>
						<th>Insert Date</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<form action="{{ route('report.progress_perkerasan_download') }}" id="formDownload" method="get">
	@csrf
	<input type="hidden" name="que" id="que">
	<input type="hidden" name="que_global" id="que_global">
</form>

@endsection

@section('my_script')
<script>
var table, table_detail, col, que

$(document).ready(()=>{
	
	Ladda.bind('.btn-ladda-spinner', {
            dataSpinnerSize: 16,
            timeout: 2000
        });
	
	$('.select-clear').select2({
		// placeholder: 'Select a State',
		allowClear: true
	});
	
	loadGrid();
	
	$('#reloadGrid').click(()=>{
		table.destroy()
		loadGrid()
	})
	
	formRequiredMark()
	
});

function edit(id,max,cur){
	var maxi = (max-cur);
	if(maxi==0){
		swal({
			title: 'For your information',
			text: 'Jalan ini sudah dilakukan perkerasan jalan.',
			type: 'info'
		});
		return false
	};
	$('#rc_id').val(id)
	$('#modal_edit').modal('show')
	$('.tsp').html('<input required type="number" value="1" min="1" name="length" id="rc_length" placeholder="Total Length" class="touchspin-vertical'+id+' form-control">')
	$('.touchspin-vertical'+id).TouchSpin({
			max: maxi,
            verticalbuttons: true,
            verticalupclass: 'icon-arrow-up22',
            verticaldownclass: 'icon-arrow-down22'
        });
	return false;
}

function detail(btn, id,max,cur, kode){
	if(table_detail){
		table_detail.destroy()
	};
	$(btn).html('<i class="icon-spinner spinner"></i> History')
	loadGridDetail(btn, "{{ URL::to('api/history/progress-perkerasan-detail') }}/"+id, kode )
	return false;
}

function del(url){
	swal({
		title: 'Anda yakin ingin menghapus data?',
		text: "",
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Hapus',
		cancelButtonText: 'Batal',
		confirmButtonClass: 'btn btn-success',
		cancelButtonClass: 'btn btn-danger',
		buttonsStyling: false
	}).then(function (is) {
		if(is.value){
			swal(
				'Terhapus!',
				'Data telah dihapus',
				'success'
			);
			setTimeout(function(){
				window.location.href = url;
			}, 1000);
		}else{
			
		}
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
				responsive: false,
				columnDefs: [
					{ 
						orderable: false,
						width: 250,
						targets: [ 0 ]
					},
				],
				dom: '<"datatable-header"Bfrl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
				buttons: [
						{
							text: '<i class="icon-file-excel"></i><o></o> Export to CSV',
							className: 'btn bg-teal-400',
							action: function(e, dt, node, config) {
								
								// dt.page.len( -1 ).draw()								
								// donlot = true
								
								que = [];
								que_global = $('input[type="search"]').val()
								var tbll = $('.datatable-responsive').find('.tfsearch').length / 2
								$('.datatable-responsive').find('.tfsearch').each((k,v)=>{
									if( $(v).val() != '' ){
										console.log(k-tbll)
										que.push( { col: col[k-tbll]['name'], val : $(v).val()} )
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
					// console.log( api.rows( {page:'current'} ).data() );
				},
				language: {
					search: '<span>Filter:</span> _INPUT_',
					searchPlaceholder: 'Type to filter...',
					lengthMenu: '<span>Show:</span> _MENU_',
					paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' },
					processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
				},
				
			});
	
	col = [			
			{ data: 'company_name', 	name: 'company_name' },
            { data: 'estate_name', 		name: 'estate_name' },
            { data: 'afdeling_code', 	name: 'afdeling_code' },
            { data: 'block_name', 		name: 'block_name' },
            { data: 'month', 			name: 'month' },
            { data: 'year', 			name: 'year' },
            { data: 'status_name', 		name: 'status_name' },
            { data: 'category_name', 	name: 'category_name' },
            { data: 'segment', 			name: 'segment' },
            { data: 'road_name', 		name: 'road_name' },
            { data: 'road_code', 		name: 'road_code' },
            { data: 'total_length', 	name: 'total_length' },
            { data: 'length', 			name: 'length' },
            { data: 'asset_code', 		name: 'asset_code' },
        ];

	table = $('.datatable-responsive').DataTable( {
        processing: true,
		'processing': true,
        serverSide: true,
        // ajax: '{{ route("report.progress_perkerasan_datatables") }}',
		ajax: $.fn.dataTable.pipeline( {
            url: '{{ route("report.progress_perkerasan_datatables") }}',
            pages: 5 // number of pages to cache
        } ),
		scrollX: true,
		scrollY: '350px',
		scrollCollapse: true,
		// fixedColumns: {
			// leftColumns: 1,
			// rightColumns: 0,
		// },
        columns: col,
		initComplete: function () {
			this.api().columns().every(function (k) {
				if(k >= 0 && k < 12){
					if(k == 6){
						var column = this;
						var dStatus = '<option value="PRODUKSI">PRODUKSI</option><option value="NON PRODUKSI">NON PRODUKSI</option><option value="UMUM">UMUM</option>';
						var select = $('<select class="form-control tfsearch"><option value="">'+dStatus+'</option></select>')
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
	
	// table.on( 'order.dt search.dt page.dt', function () {
        // table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            // cell.innerHTML = i+1;
        // } );
		// console.log('i am in')
    // } ).draw();
}

function loadGridDetail(btn, url, kode){
	$.extend( $.fn.dataTable.defaults, {
				autoWidth: false,
				responsive: true,
				columnDefs: [
					{ 
						orderable: false,
						targets: [ 0,1,2,3,4 ]
					},
					
					{ 
						class: 'alpha-primary',
						targets: [ 0]
					},
				],
				dom: '<"datatable-header"frl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
				language: {
					search: '<span>Filter:</span> _INPUT_',
					searchPlaceholder: 'Type to filter...',
					lengthMenu: '<span>Show:</span> _MENU_',
					paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' },
					processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
				}
			});
	

	table_detail = $('.datatable-detail').DataTable( {
        processing: true,
		'processing': true,
        serverSide: true,
        ajax: url,
        columns: [
            { data: 'length', 		name: 'length' },
            { data: 'month', 		name: 'month' },
            { data: 'year', 		name: 'year' },
            { data: 'updated_by', 	name: 'updated_by' },
            { data: 'created_at', 	name: 'created_at' },
        ],
		initComplete: function () {
			this.api().columns().every(function (k) {
				if(k > -1 && k < 5){
					var column = this;
					var input = document.createElement("input");
					$(input).appendTo($(column.footer()).empty())
					.on('change', function () {
						column.search($(this).val(), false, false, true).draw();
					}).attr('placeholder',' Search').addClass('form-control');
				}
			});
			
			$('#detailKodeJalan').html(kode)
			$('#modal_detail').modal('show')
			$(btn).html('<i class="icon-list3"></i> History')
		}
    } );
}
</script>
@endsection