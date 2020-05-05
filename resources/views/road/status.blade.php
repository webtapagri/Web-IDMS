@extends('layouts.app')

@section('title', 'Road Status list')

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

@section('content')

<div class="card">
<?php print_r(Session::get('job_code')); ?>
	<div class="card-header header-elements-inline">
		@if($data['access']->create == 1)
		<button 
			data-toggle="modal" data-target="#modal_add"
			type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left"><b><i class="icon-plus3"></i></b> Tambah</button>
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
				<th>Road Status</th>
				<th>Road Status Code</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		<!-- <tfoot>
			<tr>
				<th>Pencarian</th>
				<th>Road Status</th>
				<th>Kode Road Status</th>
				<th class="text-center"></th>
			</tr>
		</tfoot> -->
	</table>
</div>

<div id="modal_add" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Road Status</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<form action="{{ route('master.road_status_save') }}" method="post" class="form-horizontal">
				@crsf
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Status</label>
						<div class="col-sm-9">
							<input type="text" name="status_name" maxlength="255" placeholder="Road status" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Status Code</label>
						<div class="col-sm-9">
							<input type="number" name="status_code" maxlength="255" placeholder="Road status code" class="form-control">
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn bg-primary">Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="modal_edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Road Status</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<form action="{{ route('master.road_status_update') }}" method="post" class="form-horizontal">
				@csrf
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Status</label>
						<div class="col-sm-9">
							<input type="hidden" name="id" id="rs_id">
							<input type="text" name="status_name" id="rs_status_name" maxlength="255" placeholder="Road status" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Status Code</label>
						<div class="col-sm-9">
							<input type="number" name="status_code" id="rs_status_code" maxlength="5" placeholder="Road status code" class="form-control">
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn bg-primary">Simpan</button>
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
		// table.destroy()
		fixedColumns().update();
		$('.table').dataTable().clear();
   		$('.table').dataTable().destroy();
		loadGrid()
		console.log(123)
	})
	
	formRequiredMark()
	
});

function edit(id, txt, code){
	$('#rs_id').val(id)
	$('#rs_status_name').val(txt)
	$('#rs_status_code').val(code)
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
			alert(2)
		}
	});
}

function loadGrid(){

	
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
				responsive: true,
				columnDefs: [
					{ 
						orderable: false,
						width: 250,
						targets: [ 2 ]
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
				dom: '<"datatable-header"rl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
				language: {
					search: '<span>Filter:</span> _INPUT_',
					searchPlaceholder: 'Type to filter...',
					lengthMenu: '<span>Show:</span> _MENU_',
					paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
				}
			});
	

	table = $('.datatable-responsive').DataTable( {
        processing: true,
        serverSide: true,
		orderCellsTop: true,
        ajax: '{{ route("master.road_status_datatables") }}',
		"order": [[1,"asc"],[2, "asc" ]],
        columns: [
            // { data: 'no', 	name: 'no' },
            { data: 'status_name', 	name: 'status_name' },
            { data: 'status_code', 	name: 'status_code' },
            { data: 'action', 		name: 'action' },
        ],
		
    } );
}
</script>
@endsection