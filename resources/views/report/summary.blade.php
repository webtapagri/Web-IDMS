@extends('layouts.app')

@section('title', 'Summary')

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
				<span class="text-semibold">Error!</span> {!! \Session::get('error') !!}
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
		
		<form method="get" class="form-horizontal needs-validation" novalidate>
			@csrf
			<div class="modal-body">
				
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Plant</label>
					<div class="col-sm-9">
						<select required name="werks" id="werks"  class="form-control werks">
							<option value=""></option>
						</select>
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Tahun</label>
					<div class="col-sm-9">
						<select required name="year" id="year"  class="form-control year">
							<option value=""></option>
						</select>
					</div>
				</div>

				<div class="form-group row">
				</div>
				<div class="report">
				</div>

				
			</div>

		</form>

	</div>
</div>			
		

@endsection

@section('my_script')
<script>

$(document).ready(()=>{
	
	load_werks();
	load_year();
	// loadGrid();
	
	// $('#reloadGrid').click(()=>{
	// 	table.destroy()
	// 	loadGrid()
	// 	console.log(123)
	// })
	
	// formRequiredMark()
	
});

$('.werks').change(()=>{
	var id = $('.werks').val()
	load_report(id)
})

$('.year').change(()=>{
	var id = $('.werks').val()
	load_report(id)
})



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

function load_year(){
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('report/get_year') }}/",
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
			var htm = '<option value="0">-- Pilih Tahun --</option>'
			var htm2 = '<option value="0">-- Pilih Tahun --</option>'
			$.each(cont, (k,v)=>{
				htm += '<option value="'+v.tahun+'" >'+v.tahun+'</option>'
				htm2 += '<option value="'+v.tahun+'" id="comboid_'+v.tahun+'">'+v.tahun+'</option>'
			});
			$('#year').html(htm);
			$('#year_edit').html(htm2);
		}else{
			$('#year').html('<option value="">Gagal mengambil data</option>');	
			$('#year_edit').html('<option value="">Gagal mengambil data</option>');	
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server");
		
	});
}



function load_report(x=null){

	var werks = $('.werks').val()
	var year = $('.year').val()

	$.ajax({
		type: 'GET',
		url: "{{ URL::to('report/summary-data') }}"+"/"+ werks + "/" + year,
		data: null,
		success: function(data) {
						$(".report").html(data.html);    
					},
		error: function() {
			alert('Not OKay');
		}
	});

};



function load_report(x=null){

	var werks = $('.werks').val()
	var year = $('.year').val()

	$.ajax({
		type: 'GET',
		url: "{{ URL::to('report/summary-data') }}"+"/"+ werks + "/" + year,
		data: null,
		success: function(data) {
						$(".report").html(data.html);    
					},
		error: function() {
			alert('Not OKay');
		}
	});

};


</script>
@endsection