@extends('layouts.app')

@section('title', 'Closing Period')

@section('theme_css')

@endsection

@section('theme_js')

<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/bootbox.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/extensions/jquery_ui/interactions.min.js') }}"></script> 
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/inputs/touchspin.min.js') }}"></script>

@endsection

@section('content')

<div class="card">
	<div class="card-header header-elements-inline">
		<button 
			data-toggle="modal" data-target="#modal_add"
			type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left"><b><i class="icon-plus3"></i></b> Tambah</button>
		<!--<!-- @if($data['access']->create == 1) -->
		<!-- @endif -->
	
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
				<th>Estate</th>
				<th>BA Code</th>
				<th>Month</th>
				<th>Year</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		
	</table>
</div>

<div id="modal_add" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
            <div class="modal-header">
				<h5 class="modal-title">Tambah Closing Period</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>


			<form action="{{ route('setting.period_save') }}" method="post" class="form-horizontal">
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

				<input type="hidden" id="id" name="id">
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-3">BA Code</label>
						<div class="col-sm-9">
							<select required name="werks" id="werks"  class="form-control">
								<option value=""></option>
							</select>
						</div>
					</div>
					<?php 
					$start = $month = strtotime('2009-01-01');
					$end = strtotime('2010-01-01');
					$curdate = strtotime(date('Y-m-d'));
					$m = date('m',$curdate);
					$s="";								

					?>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Bulan</label>
						<div class="col-sm-9">
							<select required data-placeholder="Select Month" name="month"  class="form-control month">
								<option value=""></option>
								<?php
									while($month < $end){
												if(date('m', $month)== $m){
													$s = "selected='selected'";
												}else{
													$s = "";
												}
									?>
									<option value="{{ date('m', $month) }}" {{$s}} >{{ date('F', $month) }}</option>
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
					$y = date('Y',$curdate);
					$sl="";
					?>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Tahun</label>
						<div class="col-sm-9">
							<select required data-placeholder="Select Year" name="year"  class="form-control year">
								<option value=""></option>
								<?php
								$y = date('Y',$curdate);
								$sl="";
								 while($mon < $en){ 
									 
									if(date('Y', $mon)== $y){
										$sl = "selected='selected'";
									}else{
										$sl = "";
									}
									 ?>
									<option value="{{ date('Y', $mon) }}" {{$sl}} >{{ date('Y', $mon) }}</option>
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
						<span class="ladda-label">Simpan</span>
						<span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div id="modal_edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Update Closing Period</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<form action="{{ route('setting.period_update') }}" method="post" class="form-horizontal">
				@csrf
				<input type="hidden" id="p_id" name="id">
				<div class="modal-body">
                <div class="form-group row">
						<label class="col-form-label col-sm-3">BA Code</label>
						<div class="col-sm-9">
							<select required name="werks" id="werks_edit"  class="form-control">
								<option value=""></option>
							</select>
						</div>
					</div>
					<?php 
					$start = $month = strtotime('2009-01-01');
					$end = strtotime('2010-01-01');
					?>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Bulan</label>
						<div class="col-sm-9">
							<select required data-placeholder="Select Month" name="month" id="month"  class="form-control month">
								<option value=""></option>
								<?php while($month < $end){ ?>
									<option value="{{ date('m', $month) }}" >{{ date('F', $month) }}</option>
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
							<select required data-placeholder="Select Year" name="year" id="year"  class="form-control year">
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
						<span class="ladda-label">Simpan</span>
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
	load_werks()
	
	$('#reloadGrid').click(()=>{
		table.destroy()
		loadGrid()
		console.log(123)
	})
	
	formRequiredMark()
	
});

function edit(id, wk, mt, yr){
	// load_werks()
    $('#p_id').val(id)
	$('#comboid_'+wk).attr('selected','selected')
	$('#month').val(mt)
	$('#year').val(yr)
	$('#modal_edit').modal('show')
	return false;
}

// $('#modal_edit').on('show.bs.modal', function () {
// 	load_werks();
// })

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


function load_werks(){
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('api/master/werks') }}/",
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
			var htm = '<option value="">-- Pilih BA Code --</option>'
			var htm2 = '<option value="">-- Pilih BA Code --</option>'
			$.each(cont, (k,v)=>{
				htm += '<option value="'+v.werks+'" >'+v.werks+' - '+v.estate_name+'</option>'
				htm2 += '<option value="'+v.werks+'" id="comboid_'+v.werks+'">'+v.werks+' - '+v.estate_name+'</option>'
			});
			$('#werks').html(htm);
			$('#werks_edit').html(htm2);
		}else{
			$('#werks').html('<option value="">Gagal mengambil data</option>');	
			$('#werks_edit').html('<option value="">Gagal mengambil data</option>');	
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server");
		
	});
}



function loadGrid(){
	
	$('.datatable-responsive thead tr').clone(true).appendTo( '.datatable-responsive thead' );
    $('.datatable-responsive thead tr:eq(1) th').each( function (i) {
		var title = $(this).text();
		if(title !="Action"){
			$(this).html( '<input type="text" class ="form-control tfsearch" placeholder="Search" />' );
	
			$( 'input', this ).on( 'click change', function () {
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
						targets: [ 0 ]
					},
					{ 
						orderable: false,
						targets: [ 0 ]
					},
					{ 
						orderable: false,
						targets: [ 4 ]
					},
				],
				dom: '<"datatable-header"rl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
				language: {
					search: '<span>Filter:</span> _INPUT_',
					searchPlaceholder: 'Type to filter...',
					lengthMenu: '<span>Show:</span> _MENU_',
					paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
				},
				
			});
	

	table = $('.datatable-responsive').DataTable( {
        processing: true,
		'processing': true,
        serverSide: true,
		orderCellsTop: true,
        ajax: '{{ route("setting.period_datatables") }}',
		"order": [[1,"asc"],[2, "asc" ]],
        columns: [
            { data: 'estate_name', 	name: 'estate_name' },
            { data: 'werks', 	name: 'werks' },
            { data: 'month', 	name: 'month' },
            { data: 'year', 	name: 'year' },
            { data: 'action', 		name: 'action' },
        ],
		
    } );

}

</script>
@endsection