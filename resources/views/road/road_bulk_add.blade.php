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
				<button type="button" class="btn btn-default validation-check">Cek Validasi</button>
				<button type="submit" class="btn btn-primary">Simpan</button>
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
				{company_code: "", estate_code:"", werks: "", afdeling_code: "", block_code: "", status_id: "", category_id: "", segment: ""
				, road_code: "", total_length: "", asset_code: ""},
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
		
		hot_context_copy_init = new Handsontable(hot_context_copy, {
				data: car_data,
				rowHeaders: true,
				stretchH: 'all',
				colHeaders: ['Company Code', 'Estate Code', 'Plant', 'Afdeling Code', 'Block Code','Status Code','Category Code','Segment'
				,'Road Code','Total Length','Asset Code'],
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
						validator: cekString
					},
					{
						data: 'block_code',
						validator: cekString
					},
					{
						data: 'status_id',
						type: 'numeric',
						validator: cekNum
					},
					{
						data: 'category_id',
						type: 'numeric',
						validator: cekNum
					},
					{
						data: 'segment',
						type: 'numeric',
						validator: cekNum
					},
					{
						data: 'road_code',
						type: 'numeric',
						validator: cekNum
					},
					{
						data: 'total_length',
						type: 'numeric',
						validator: cekNum
					},
					{
						data: 'asset_code',
						validator: cekString
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
									text: 'Kolom '+prop+' baris ke '+(row+1)+' harus diisi dan harus berupa angka.',
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
	var valid
	hot_context_copy_init.validateCells((isValid)=>{
		valid = isValid    
	})
	console.log(valid)
	
}
</script>
@endsection