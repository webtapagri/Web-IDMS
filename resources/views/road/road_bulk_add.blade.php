@extends('layouts.app')

@section('title', 'Bulk Add')

@section('theme_js')
<script src="{{ asset('limitless/global_assets/js/plugins/tables/handsontable/handsontable.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/tables/handsontable/sheetclip.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/noty.min.js') }}"></script>
@endsection

@section('content')

<div class="card">
	<div class="card-header header-elements-inline">
		<h5 class="card-title">Copy-paste</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
				<a class="list-icons-item" data-action="reload"></a>
			</div>
		</div>
	</div>

	<div class="card-body">
		<div class="row mb-3">
			<div class="col-md-8">
				<p class="">Gunakan shortcuts <kbd>Ctrl (Cmd) + C</kbd> and <kbd>Ctrl (Cmd) + V</kbd> untuk menyalin data dari file excel atau csv anda.</p>
			</div>
			<div class="col-md-4 text-right">
			{{-- 
				<button type="button" class="btn btn-default validation-check">Cek Validasi</button>
			--}}
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		</div>
		
		<div class="row">
			<div class="col-12">
				<div class="alert error alert-danger alert-dismissible d-none">
					<span class="error_area"></span>
				</div>
			</div>
			
			<div class="col-12">
				<div class="alert success alert-success alert-dismissible d-none">
					<span class="success_area"></span>
				</div>
			</div>
		</div>
		
		<div class="hot-container">
			<!-- <pre id="example1console" class="console"></pre>
			<div id="hot_context_copy" class="handsontable"></div> -->
			<div id="hot_context_copy"></div>
		</div>
	</div>
</div>

@endsection

@section('my_script')
<script>
var HotContextMenu, hot_context_copy_init;
$(document).ready(()=>{
	HotContextMenu.init();
	
	Noty.overrideDefaults({
            theme: 'limitless',
            layout: 'topRight',
            type: 'alert',
            timeout: 3500
        });
	
	$('a[data-action="reload"]').click(()=>{
		HotContextMenu.init()
	})
	
	$('button[type="submit"]').click(()=>{
		save()
	})
	
	$('.validation-check').click(()=>{
		hot_context_copy_init.validateCells((isValid)=>{
			if(isValid){
				new Noty({
					text: 'Semua data valid.',
					type: 'success'
				}).show();
			}  
		})
	})
})

HotContextMenu = function() {
	var _componentHotContextMenu = function() {
			if (typeof Handsontable == 'undefined') {
				console.warn('Warning - handsontable.min.js is not loaded.');
				return;
			}	
		var car_data = [
				{company_code: "", estate_code:"", werks: "", afdeling_code: "", block_code: "", status_code: "", category_code: "", segment: ""
				, total_length: "", asset_code: ""},
			];
			
		var hot_context_copy = document.getElementById('hot_context_copy');
		$('#hot_context_copy').html('')
		var sheetclip = new SheetClip();
		var clipboardCache = '';
		
		var cekString = function(value,callback){
			if (value == '' || value === null) {
			  callback(false);
			}
			else {
			  callback(true);
			}
		}
		var cekNum = function(value,callback){
			if (value == '' || value === null || typeof(value) == 'string') {
			  callback(false);
			}
			else {
			  callback(true);
			}
		}
		var cekNumSegment = function(value,callback){
			if (value == '' || value === null || typeof(value) == 'string') {
			  callback(false);
			}
			else {
				if(value > 9){
					callback(false);
				}else{
					callback(true);	
				}
			}
		}
		
		hot_context_copy_init = new Handsontable(hot_context_copy, {
				data: car_data,
				rowHeaders: true,
				stretchH: 'all',
				colHeaders: ['Company Code', 'Estate Code', 'Plant', 'Afdeling Code', 'Block Code','Status Code','Category Code','Segment'
				,'Total Length','Asset Code'],
				columns: [
					{
						data: 'company_code',
						type: 'numeric',
						className: 'htLeft',
						width: 50,
						validator: cekNum
					},
					{
						data: 'estate_code',
						type: 'numeric',
						validator: cekNum
					},
					{
						data: 'werks',
						type: 'numeric',
						validator: cekNum
					},
					{
						data: 'afdeling_code',
						// validator: cekString
					},
					{
						data: 'block_code',
						// validator: cekString
					},
					{
						data: 'status_code',
						type: 'numeric',
						validator: cekNum
					},
					{
						data: 'category_code',
						type: 'numeric',
						validator: cekNum
					},
					{
						data: 'segment',
						type: 'numeric',
						validator: cekNumSegment
					},
					
					{
						data: 'total_length',
						type: 'numeric',
						validator: cekNum
					},
					{
						data: 'asset_code',
						// validator: cekString
					},
				],
				afterCopy: function(changes) {
					clipboardCache = sheetclip.stringify(changes);
				},
				afterCut: function(changes) {
					clipboardCache = sheetclip.stringify(changes);
				},
				afterPaste: function(changes) {
					clipboardCache = sheetclip.stringify(changes);
				},
				afterValidate: function(isValid, value, row, prop){
					if(!isValid){
						if(value == 'afdeling_code' && value == 'block_code'  && value == 'asset_code'){
							new Noty({
								text: 'Kolom '+prop+' baris ke '+(row+1)+' harus diisi.',
								type: 'warning'
							}).show();
						}else{
							if(value=='0'||value==0){
								new Noty({
									text: 'Kolom '+prop+' baris ke '+(row+1)+' harus lebih dari 0 meter.',
									type: 'warning'
								}).show();
							}else{
								new Noty({
									text: 'Kolom '+prop+' baris ke '+(row+1)+' harus diisi dan harus berupa angka dan sesuai format.',
									type: 'warning'
								}).show();
							}

						}
					}
				},
				contextMenu: [
					'row_above',
					'row_below',
					'remove_row',
					'---------',
					'copy',
					'cut',
					{
						key: 'paste',
						name: 'Paste',
						disabled: function() {
							return clipboardCache.length === 0;
						},
						callback: function() {
							var plugin = this.getPlugin('copyPaste');

							this.listen();
							plugin.paste(clipboardCache);
						}
					}
				]
			});
	};
	return {
		init: function() {
			_componentHotContextMenu();
		}
	}
}();


function save(){
	hot_context_copy_init.validateCells((isValid)=>{
		if(isValid){
			window.onbeforeunload = function() {
				return 'Data sedang diproses, apakah anda ingin membatalkan ?';
			};
			
			let dataFix = [];
			$.each(hot_context_copy_init.getData(), (k,v)=>{
				dataFix.push({company_code: v[0], estate_code:v[1], werks: v[2], afdeling_code: v[3], block_code: v[4], status_code: v[5], category_code: v[6], segment: v[7], total_length: v[8], asset_code: v[9]}) });
			
			$.ajax({
				type:'post',
				url:"{{ URL::to('master/road-bulk-save') }}",
				data:{ data: dataFix },
				cache:false,
				beforeSend:function(){
					HoldOn();
					$('.error').addClass('d-none')
					$('.error_area').html('')
					$('.success').addClass('d-none')
					$('.success_area').html('')
				},
				complete:function(){
					HoldOff();
					window.onbeforeunload = null
				},
				headers: {
					"Access-Control-Allow-Origin":"*",
					"X-CSRF-TOKEN": "{{ csrf_token() }}",
				},
				success:function(rsp){
					if(rsp.code==200){
						let cont = rsp.contents
						if(cont.error.length > 0){
							$('.error').removeClass('d-none');
							$.each(cont.error, (k,v)=>{
								$('.error_area').append('Error at line '+v.line+' with status <strong>'+v.status+'<strong><br/>');
							})
						}
						if(cont.success.length > 0){
							$('.success').removeClass('d-none');
							$('.success_area').append('Berhasil memproses baris ke: ');
							// var succ = cont.success.filter( distinct )
							$.each(cont.success, (k,v)=>{
								var km = k != 0 ? ',' : ''
								$('.success_area').append( v+km );
							})
						}
					}else{
						alert("Respon error. "+rsp.code+" - "+rsp.contents);
					}
				}
			})
				
		}
	})
	
}
</script>
@endsection