@extends('layouts.app')

@section('title', 'Tambah Road Master')

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

<!-- velocity anime -->
<script src="{{ asset('limitless/global_assets/js/plugins/velocity/velocity.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/velocity/velocity.ui.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/ui/prism.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/demo_pages/animations_velocity_examples.js') }}"></script>

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
		
		<form action="{{ route('master.road_save') }}" method="post" class="form-horizontal">
			@csrf
			<div class="modal-body">
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Company</label>
					<div class="col-sm-9">
						<select data-placeholder="Select a Company" name="company_code"  class="form-control select-clear company_code" data-fouc>
							<option value=""></option>
						</select>
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Estate</label>
					<div class="col-sm-9">
						<select  data-placeholder="Select a Estate" name="werks"  class="form-control select-clear estate_code" data-fouc>
							<option value=""></option>
						</select>
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Afdeling</label>
					<div class="col-sm-9">
						<select  data-placeholder="Select Afdeling" name="afdeling_code"  class="form-control select-clear afdeling_code" data-fouc>
							<option value=""></option>
						</select>
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Block</label>
					<div class="col-sm-9">
						<select data-placeholder="Select Block" name="block_code"  class="form-control select-clear block_code" data-fouc>
							<option value=""></option>
						</select>
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Road Status</label>
					<div class="col-sm-9">
						<select data-placeholder="Select Road Status" name="status_id"  class="form-control select-clear status_id" data-fouc>
							<option value=""></option>
						</select>
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Road Category</label>
					<div class="col-sm-9">
						<select data-placeholder="Select Category" name="category_id"  class="form-control select-clear category_id" data-fouc>
							<option value=""></option>
						</select>
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Segment</label>
					<div class="col-sm-9">
						<input type="number" name="segment"  min="0" class="form-control" value="{{ old('segment') }}">
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Length (m)</label>
					<div class="col-sm-9">
						<input type="number" name="total_length" min="0"  class="form-control" value="{{ old('total_length') }}">
					</div>
				</div>
				
				<!--  
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Road Code</label>
					<div class="col-sm-9">
						<input type="text" name="road_code"  class="form-control" value="{{ old('road_code') }}">
					</div>
				</div>
				
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Road Name</label>
					<div class="col-sm-9">
						<input type="text" name="road_name"  class="form-control" value="{{ old('road_name') }}">
					</div>
				</div>
				-->
				
				<div class="form-group row">
					<label class="col-form-label col-sm-3 norequired">Asset Code</label>
					<div class="col-sm-9">
						<input type="text" name="asset_code"  class="form-control" value="{{ old('asset_code') }}">
					</div>
				</div>
				
			</div>

			<div class="modal-footer">
				<a href="{{ route('master.road') }}"><button type="button" class="btn btn-link">Kembali</button></a>
				<button type="submit" class="btn btn-primary btn-ladda btn-ladda-spinner ladda-button legitRipple" data-style="expand-left" data-spinner-color="#333" data-spinner-size="20">
					<span class="ladda-label">SImpan</span>
					<span class="ladda-spinner"></span><div class="ladda-progress" style="width: 0px;"></div>
				</button>
			</div>
		</form>

	</div>
</div>

@endsection

@section('my_script')
<script>
var table

$(document).ready(()=>{
	Ladda.bind('.btn-ladda-spinner', {
            dataSpinnerSize: 16,
            timeout: 2000
        });
		
	load_company();
	load_status();
	
	$('.select-clear').select2({
		allowClear: true
	});
	formRequiredMark()
});

$('.company_code').change(()=>{
	var id = $('.company_code').val()
	load_estate(id)
})

$('.estate_code').change(()=>{
	var id = $('.estate_code').val()
	load_afdeling(id)
})

$('.afdeling_code').change(()=>{
	var id = $('.afdeling_code').val()
	var w = $('.estate_code').val()
	load_block(id,w)
})

$('.status_id').change(()=>{
	var id = $('.status_id').val()
	load_category(id)
})

function load_company(){
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
				$('.company_code').append('<option value="'+v.company_code+'">'+v.company_code+' - '+v.company_name+'</option>')
			})
		}else{
			$('.company_code').html(rsp.code+' - '+rsp.contents)
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server");
		
	});
}

function load_estate(id){
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('api/master/estate_tree/') }}/"+id,
		data: null,
		cache:false,
		beforeSend:function(){
			$('.estate_code').html('<option value=""></option>')
			HoldOn(light)
		},
		complete:function(){
			HoldOff(light)
		},
		headers: {
			"X-CSRF-TOKEN": "{{ csrf_token() }}"
		}
	}).done(function(rsp){
		
		if(rsp.code=200){
			var cont = rsp.contents
			$.each(cont, (k,v)=>{
				$('.estate_code').append('<option value="'+v.werks+'-'+v.estate_code+'">'+v.werks+' - '+v.estate_name+'</option>')
			})
		}else{
			$('.estate_code').html(rsp.code+' - '+rsp.contents)
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server");
		
	});
}

function load_afdeling(id){
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('api/master/afdeling_tree/') }}/"+id,
		data: null,
		cache:false,
		beforeSend:function(){
			$('.afdeling_code').html('<option value=""></option>')
			HoldOn()
		},
		complete:function(){
			HoldOff()
		},
		headers: {
			"X-CSRF-TOKEN": "{{ csrf_token() }}"
		}
	}).done(function(rsp){
		
		if(rsp.code=200){
			var cont = rsp.contents
			$.each(cont, (k,v)=>{
				$('.afdeling_code').append('<option value="'+v.afdeling_code+'">'+v.afdeling_code+' - '+v.afdeling_name+'</option>')
			})
		}else{
			$('.afdeling_code').html(rsp.code+' - '+rsp.contents)
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server");
		
	});
}

function load_block(id,w){
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('api/master/block_tree/') }}/"+id+"/"+w,
		data: null,
		cache:false,
		beforeSend:function(){
			$('.block_code').html('<option value=""></option>')
			HoldOn()
		},
		complete:function(){
			HoldOff()
		},
		headers: {
			"X-CSRF-TOKEN": "{{ csrf_token() }}"
		}
	}).done(function(rsp){
		
		if(rsp.code=200){
			var cont = rsp.contents
			$.each(cont, (k,v)=>{
				$('.block_code').append('<option value="'+v.block_code+'-'+v.block_name+'">'+v.block_code+' - '+v.block_name+'</option>')
			})
		}else{
			$('.block_code').html(rsp.code+' - '+rsp.contents)
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server");
		
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

</script>
@endsection