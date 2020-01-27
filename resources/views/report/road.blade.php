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
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<style>
.btexcel {
    margin-right: 20px;
}
</style>

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
    
	<table class="table datatable-responsive">
		<thead>
			<tr>
				<th>Estate</th>
				<th>BA Code</th>
				<th>Afdeling</th>
				<th>Block Code</th>
				<th>Block Name</th>
				<th>Status</th>
				<th>Category</th>
				<th>Segment</th> 
				<th>Road Name</th>
				<th>Road Code</th>
				<th>Length</th>
				<th>Asset Code</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
                <th>Estate</th>
				<th>BA Code</th>
				<th>Afdeling</th>
				<th>Block Code</th>
				<th>Block Name</th>
				<th>Status</th>
				<th>Category</th>
				<th>Segment</th> 
				<th>Road Name</th>
				<th>Road Code</th>
				<th>Length</th>
				<th>Asset Code</th>
			</tr>
		</tfoot>
	</table>
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
	
	formRequiredMark()
	
});

// function edit(id, txt, code){
// 	$('#rs_id').val(id)
// 	$('#rs_status_name').val(txt)
// 	$('#rs_status_code').val(code)
// 	$('#modal_edit').modal('show')
// 	return false;
// }

// function del(url){
// 	swal({
// 		title: 'Anda yakin ingin menghapus data?',
// 		text: "",
// 		type: 'warning',
// 		showCancelButton: true,
// 		confirmButtonText: 'Hapus',
// 		cancelButtonText: 'Batal',
// 		confirmButtonClass: 'btn btn-success',
// 		cancelButtonClass: 'btn btn-danger',
// 		buttonsStyling: false
// 	}).then(function (is) {
// 		if(is.value){
// 			setTimeout(function(){
// 				window.location.href = url;
// 			}, 1000);
// 		}else{
// 			alert(2)
// 		}
// 	});
// }

function loadGrid(){
	$.extend( $.fn.dataTable.defaults, {
				autoWidth: false,
				responsive: true,
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
						searchable: false,
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
        serverSide: true,
        ajax: '{{ route("report.road_datatables") }}',
		"order": [[1,"asc"],[2, "asc" ]],
        columns: [
            
            { data: 'estate_name', 		name: 'estate_name' },
            { data: 'werks', 	    	name: 'werks' },
            { data: 'afdeling_name', 	name: 'afdeling_name' },
            { data: 'block_code', 		name: 'block_code' },
            { data: 'block_name', 		name: 'block_name' },
            { data: 'status_name', 		name: 'status_name' },
            { data: 'category_name', 	name: 'category_name' },
			{ data: 'segment',      	name: 'segment' },
            { data: 'road_name', 		name: 'road_name' },
            { data: 'road_code', 		name: 'road_code' },
            { data: 'total_length', 	name: 'total_length' },
            { data: 'asset_code', 		name: 'asset_code' },
        ],
            dom: 'Blfrtip',
            buttons: {
                        buttons: [
                            {   extend: 'excel', 
                                text : '<i class="icon-file-excel"></i> Export to Excel',
                                className: 'btn bg-teal-400 btn-labeled btexcel',
                                exportOptions: {
                                                modifier: {
                                                page: 'all',
                                                search: 'none'   
                                                }
                                },
                             }
                        ]
                    },
		initComplete: function () {
			this.api().columns().every(function (k) {
				if(k > 0 && k < 12){
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
table.buttons.info( 'Notification', 'This is a notification message!', 3000 );
}
</script>
@endsection