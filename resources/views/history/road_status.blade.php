@extends('layouts.app')

@section('title', 'Perubahan Status')

@section('theme_css')

@endsection

@section('theme_js')
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
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
		@if($access['create']=='1')
		<!--  
		<a href="{{ route('master.road_add') }}">
			<button 
				data-toggle="modal"
				type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left"><b><i class="icon-plus3"></i></b> 
				Tambah
			</button>
		</a>
		-->
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
	<table class="table datatable-responsive table-xs display">
		<thead>
			<tr>
				<th>Company</th> 
				<th>Estate</th>
				<th>Afdeling</th>
				<th>Block</th>
				<th>Status</th>
				<th>Category</th>
				<th>Segment</th>
				<th>Road Name</th>
				<th>Road Code</th>
				<th>Length</th>
				<th>Asset Code</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		
	</table>
</div>

<div id="modal_edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Update Status Jalan</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<form action="{{ route('history.road_status_update') }}" method="post" class="form-horizontal">
				@csrf

				
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

				<input type="hidden" id="rc_id" name="road_id">
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Status</label>
						<div class="col-sm-9">
							<select required data-placeholder="Select Road Status" name="status_id" id="status_id"  class="form-control select-clear status_id" data-fouc>
								<option value=""></option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Category</label>
						<div class="col-sm-9">
							<select required data-placeholder="Select Road Category" name="category_id" id="category_id"  class="form-control select-clear category_id" data-fouc>
								<option value=""></option>
							</select>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Segment</label>
						<div class="col-sm-9">
							<input type="number" name="segment"  min="1" max="9" class="form-control" value="{{ old('segment') }}">
						</div>
					</div>
					<!-- <div class="form-group row">
						<label class="col-form-label col-sm-3">Panjang Perkerasan (m)</label>
						<div class="col-sm-9 tsp">
							
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Kode Asset</label>
						<div class="col-sm-9 tsp">
							
						</div>
					</div> -->
					
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner ladda-button legitRipple" data-style="expand-left" data-spinner-color="#333" data-spinner-size="20">
						<span class="ladda-label">Simpan</span>
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
				<h5 class="modal-title">Detail Perubahan Status Jalan</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<table class="table datatable-detail table-xs">
				<thead>
					<tr>
						<th>Status</th>
						<th>Category</th>
						<th>Segment</th>
						<th>Road Name</th>
						<th>Road Code</th>
						<th>Total Length</th>
						<th>Updated by</th>
						<th>Insert Date</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Status</th>
						<th>Category</th>
						<th>Segment</th>
						<th>Road Name</th>
						<th>Road Code</th>
						<th>Total Length</th>
						<th>Updated by</th>
						<th>Insert Date</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

@endsection

@section('my_script')
<script>
var table, table_detail

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
	// loadStatus();
	
	$('#reloadGrid').click(()=>{
		table.destroy()
		loadGrid()
	});
		
});

$('#modal_edit').on('show.bs.modal', function () {
	load_status();
	$('.status_id').change(()=>{
		var id = $('.status_id').val()
		load_category(id)
	})
})

function edit(id,stat){
	var st_id = $('#status_id').val();
	if(st_id==stat){
		swal({
			title: 'For your information',
			text: 'Jalan ini sudah dilakukan perubahan status.',
			type: 'info'
		});
		return false
	};
	$('#rc_id').val(id)
	$('#modal_edit').modal('show')
	// $('.tsp').html('<input required type="number" value="1" min="1" name="length" id="rc_length" placeholder="Total Length" class="touchspin-vertical'+id+' form-control">')
	// $('.touchspin-vertical'+id).TouchSpin({
	// 		max: maxi,
    //         verticalbuttons: true,
    //         verticalupclass: 'icon-arrow-up22',
    //         verticaldownclass: 'icon-arrow-down22'
    //     });
	return false;
}

function detail(btn,id,stat){
	if(table_detail){
		table_detail.destroy()
	};
	$(btn).html('<i class="icon-spinner spinner"></i>')
	loadGridDetail(btn, "{{ URL::to('api/history/road-status-detail') }}/"+id )
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

function load_status(){
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('api/master/road-status') }}/",
		data: null,
		cache:false,
		beforeSend:function(){
			$('.status_id').html('<option value=""></option>')
			HoldOn(light);
		},
		complete:function(){
			HoldOff(light);
		},
		headers: {
			"X-CSRF-TOKEN": "{{ csrf_token() }}"
		}
	}).done(function(rsp){
		
		if(rsp.code=200){
			var cont = rsp.contents
			$.each(cont, (k,v)=>{
				$('.status_id').append('<option value="'+v.id+'">'+v.status_name+'</option>')
			})
		}else{
			$('.status_id').html(rsp.code+' - '+rsp.contents)
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server saat load data status (combobox)");
		
	});
}

function load_category(id){
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('api/master/road-category/') }}/"+id,
		data: null,
		cache:false,
		beforeSend:function(){
			$('.category_id').html('<option value=""></option>')
			HoldOn(light)
		},
		complete:function(){
			HoldOff(light)
		},
		headers: {
			"X-CSRF-TOKEN": "{{ csrf_token() }}"
		}
	}).done(function(rsp){
		
		$('.category_id option').remove();
		if(rsp.code=200){
			var cont = rsp.contents
			$.each(cont, (k,v)=>{
				$('.category_id').append('<option value="'+v.id+'">'+v.category_name+'</option>')
			})
		}else{
			$('.category_id').html(rsp.code+' - '+rsp.contents)
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server");
		
	});
}


function loadGrid(){
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
						targets: [ 5 ]
					},
					{ 
						orderable: false,
						targets: [ 11 ]
					},
					{
						targets: [4],
						"render": function (data, type, row, meta){
												var $select = $("<select></select>", {
												});
												$.each(times, function (k, v) {
			
													var $option = $("<option></option>", {
														"text": v,
														"value": v
													});
													if (data === v) {
														$option.attr("selected", "selected")
													}
													$select.append($option);
												});
												return $select.prop("outerHTML");
					}
					{ 
						"searchable": false, 
						"targets": 11
					},
				],
				dom: '<"datatable-header"rl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
				language: {
					search: '<span>Filter:</span> _INPUT_',
					searchPlaceholder: 'Type to filter...',
					lengthMenu: '<span>Show:</span> _MENU_',
					paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' },
					processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
				},
				
			});
	

	table = $('.datatable-responsive').DataTable( {
        processing: true,
		'processing': true,
		serverSide: true,
		orderCellsTop: true,
        // ajax: '{{ route("history.road_status_datatables") }}',
		ajax: $.fn.dataTable.pipeline( {
            url: '{{ route("history.road_status_datatables") }}',
            pages: 5 // number of pages to cache
        } ),
		scrollX: true,
		scrollY: '350px',
		scrollCollapse: true,
		fixedColumns: {
			leftColumns: 0,
			rightColumns: 1
		},
        columns: [
			{ data: 'company_name', 	name: 'company_name' },
            { data: 'estate_name', 		name: 'estate_name' },
            { data: 'afdeling_code', 	name: 'afdeling_code' },
            { data: 'block_name', 		name: 'block_name' },
            { data: 'status_name', 		name: 'TM_ROAD_STATUS.status_name' },
            { data: 'category_name', 	name: 'TM_ROAD_CATEGORY.category_name' },
            { data: 'segment', 			name: 'segment' },
            { data: 'road_name', 		name: 'road_name' },
            { data: 'road_code', 		name: 'road_code' },
            { data: 'total_length', 	name: 'total_length' },
            { data: 'asset_code', 		name: 'asset_code' },
            { data: 'action', 			name: 'action' },
        ],
		initComplete: function () {
			this.api().columns().every(function (k) {
				if(k > -1 && k < 11){
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
						
					}
					// else{
					// 	var column = this;
					// 	var input = document.createElement("input");
					// 	$(input).appendTo($(column.footer()).empty())
					// 	.on('change', function () {
					// 		column.search($(this).val(), false, false, true).draw();
					// 	}).attr('placeholder',' Search').addClass('form-control tfsearch');
					// }


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

function loadGridDetail(btn,url){
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
						targets: [ 3]
					},
				],
				dom: '<"datatable-header"rl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
				language: {
					search: '<span>Filter:</span> _INPUT_',
					searchPlaceholder: 'Type to filter...',
					lengthMenu: '<span>Show:</span> _MENU_',
					paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
				}
			});
	

	table_detail = $('.datatable-detail').DataTable( {
        processing: true,
		'processing': true,
        serverSide: true,
        ajax: url,
        columns: [
            { data: 'status_name', 		name: 'status_name' },
            { data: 'category_name', 		name: 'category_name' },
            { data: 'segment', 		name: 'segment' },
            { data: 'road_name', 		name: 'road_name' },
            { data: 'road_code', 		name: 'road_code' },
            { data: 'total_length', 		name: 'total_length' },
            { data: 'updated_by', 	name: 'updated_by' },
            { data: 'created_at', 	name: 'created_at' },
        ],
		initComplete: function () {
			this.api().columns().every(function (k) {
				if(k > -1 && k < 8){
					var column = this;
					var input = document.createElement("input");
					$(input).appendTo($(column.footer()).empty())
					.on('change', function () {
						column.search($(this).val(), false, false, true).draw();
					}).attr('placeholder',' Cari').addClass('form-control');
				}
			});
			$(btn).html('<i class="icon-list3"></i>')
			$('#modal_detail').modal('show')
		}
    } );
}
</script>
@endsection