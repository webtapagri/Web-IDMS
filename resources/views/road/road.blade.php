@extends('layouts.app')

@section('title', 'Road Master List')

@section('theme_css')

@endsection

@section('theme_js')
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/bootbox.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/extensions/jquery_ui/interactions.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>

@endsection

@section('content')

<div class="card">
	<div class="card-header header-elements-inline">
		@if($access['create']=='1')
		<button 
			data-toggle="modal" data-target="#modal_add"
			type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left"><b><i class="icon-plus3"></i></b> 
			Tambah
		</button>
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
				<span class="text-semibold">{{ \Session::get('success') }}</span> 
			</div>
		@endif
		
		@if (\Session::has('error'))
			<div class="alert alert-warning no-border">
				<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
				<span class="text-semibold">{{ \Session::get('error') }}</span> 
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
				<th>Road Code</th>
				<th>Road Name</th>
				<th>Length</th>
				<th>Asset Code</th>
				<th>Segment</th>
				<th>Status</th>
				<th>Category</th>
				<th>Company</th> 
				<th>Estate</th>
				<th>Afdeling</th>
				<th>Block</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Road Code</th>
				<th>Road Name</th>
				<th>Length</th>
				<th>Asset Code</th>
				<th>Segment</th>
				<th>Status</th>
				<th>Category</th>
				<th>Company</th> 
				<th>Estate</th>
				<th>Afdeling</th>
				<th>Block</th>
				<th class="text-center">Action</th>
			</tr>
		</tfoot>
	</table>
</div>

<div id="modal_add" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="row " style="pointer-events: auto;">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body text-center">
						<i class="icon-insert-template icon-3x text-info mt-1 mb-3"></i>
						<h6 class="font-weight-semibold">Form</h6>
						<p class="mb-3">Menambahkan data secara manual menggunakan form input</p>
						<a href="{{ route('master.road_add') }}">Pilih &rarr;</a>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-body text-center">
						<i class="icon-copy icon-3x text-primary-600 mt-1 mb-3"></i>
						<h6 class="font-weight-semibold">Copy Paste</h6>
						<p class="mb-3">Menambahkan data <i>opening balance</i> secara  <i>copy paste</i></p>
						<a href="{{ route('master.road_bulk_add') }}">Pilih &rarr;</a>
					</div>
				</div>
			</div>
			
		</div>
		<div class="row" style="pointer-events: auto;">
			<div class="col-md-12 text-center">
				<a href="" class="btn btn-link text-white" data-dismiss="modal">Close</a>
			</div>
		</div>
	</div>
</div>

<div id="modal_edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Road Master</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<form action="{{ route('master.road_update') }}" method="post" class="form-horizontal">
				@csrf
				<input type="hidden" id="rc_road_id" name="road_id">
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Total Length</label>
						<div class="col-sm-9">
							<input required type="number" name="total_length" min="1" id="rc_total_length" placeholder="Total Length" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Asset Code</label>
						<div class="col-sm-9">
							<input type="text" name="asset_code" id="rc_asset_code" maxlength="255" placeholder="Asset Code" class="form-control">
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

@endsection

@section('my_script')
<script>
var table

let hl = "{{ \Session::has('hl') }}"

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
	// loadStatus()
	
	$('#reloadGrid').click(()=>{
		table.destroy()
		loadGrid()
	})
	
	
});

function edit(id, tl, ac){
	$('#rc_road_id').val(id)
	$('#rc_total_length').val(tl)
	$('#rc_asset_code').val(ac)
	$('#modal_edit').modal('show')
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
			setTimeout(function(){
				window.location.href = url;
			}, 1000);
		}else{
			
		}
	});
}

function loadStatus(){
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('api/master/road-status') }}/",
		data: null,
		cache:false,
		beforeSend:function(){
			// HoldOn(light);
		},
		complete:function(){
			// HoldOff(light);
		},
		headers: {
			"X-CSRF-TOKEN": "{{ csrf_token() }}"
		}
	}).done(function(rsp){
		
		if(rsp.code=200){
			var cont = rsp.contents;
			var htm = '<option value="">-- Pilih Status --</option>'
			var htm2 = '<option value="">-- Pilih Status --</option>'
			$.each(cont, (k,v)=>{
				htm += '<option value="'+v.id+'" >'+v.status_name+'</option>'
				htm2 += '<option value="'+v.id+'" id="comboid_'+v.id+'">'+v.status_name+'</option>'
			});
			$('#rc_status_id').html(htm);
			$('#rc_status_id_edit').html(htm2);
		}else{
			$('#rc_status_id').html('<option value="">Gagal mengambil data</option>');	
			$('#rc_status_id_edit').html('<option value="">Gagal mengambil data</option>');	
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server");
		
	});
}

function loadGrid(){
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

	$.extend( $.fn.dataTable.defaults, {
				autoWidth: false,
				responsive: true,
				columnDefs: [
					{ 
						orderable: false,
						width: 250,
						targets: [ 5 ]
					},
					{ 
						orderable: false,
						targets: [ 0 ]
					},
				],
				dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
				language: {
					search: '<span>Filter:</span> _INPUT_',
					searchPlaceholder: 'Type to filter...',
					lengthMenu: '<span>Show:</span> _MENU_',
					paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
				}
			});
	

	table = $('.datatable-responsive').DataTable( {
        processing: true,
		'processing': true,
        serverSide: true,
        // ajax: '{{ route("master.road_datatables") }}',
        ajax: $.fn.dataTable.pipeline( {
            url: '{{ route("master.road_datatables") }}',
            pages: 100 // number of pages to cache
        } ),
		
		// "order": [[1,"asc"],[2, "asc" ]],
		
        columns: [
            { data: 'road_code', 		name: 'road_code' },
            { data: 'road_name', 		name: 'road_name' },
            { data: 'total_length', 	name: 'total_length' },
            { data: 'asset_code', 		name: 'asset_code' },
            { data: 'segment', 			name: 'segment' },
            { data: 'status_name', 		name: 'status_name' },
            { data: 'category_name', 	name: 'category_name' },
			{ data: 'company_name', 	name: 'company_name' },
            { data: 'estate_name', 		name: 'estate_name' },
            { data: 'afdeling_code', 	name: 'afdeling_code' },
            { data: 'block_name', 		name: 'block_name' },
            { data: 'action', 			name: 'action' },
        ],
		initComplete: function () {
			if(hl != ''){
				$($('tbody>tr')[0]).css('background-color','#d0eeff')
			}
			this.api().columns().every(function (k) {
				if(k ==5){
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
 
					// column.data().unique().sort().each( function ( d, j ) {
						// select.append( '<option value="'+d+'">'+d+'</option>' )
					// } );
				
					
				}else{
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
	
	// table.on( 'order.dt search.dt page.dt', function () {
        // table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            // cell.innerHTML = i+1;
        // } );
		// console.log('i am in')
    // } ).draw();
}
</script>
@endsection