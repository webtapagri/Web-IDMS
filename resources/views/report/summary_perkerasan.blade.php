<style>
.table td, .table th {
    border: none;
}

table.table.table-condensed {
    border: 1px solid black;
}
</style>

<div class="form-group row">
	<div class="col-sm-5">
		<table class="table table-borderless">
			<tr>
				<td>Plant</td><td>:</td><td>{{ $data['werks'][0] }}</td>
			</tr>
			<tr>
				<td>Tahun</td><td>:</td><td>{{ $data['year'] }}</td>
			</tr>
		</table>
	</div>
	<!-- <div class="col-sm-5">
	<button type="button" onclick="load_pdf()" class="btn btn-primary" data-style="expand-left" data-spinner-color="#333" data-spinner-size="20">Print PDF</button>
	</div> -->
</div>
<div class="form-group row">
	<div class="col-sm-7">
		<table class="table table-condensed">
			<tr>
				<th colspan="3">Jalan Produksi</th>
			</tr>
			<tr>	
				<td></td>
				<td>Total Jalan Produksi</td>
				<td>{{  number_format($data['jum_produksi']) }} Unit</td>
			</tr>
			<tr>	
				<td></td>
				<td>&Sigma; Panjang Jalan Produksi</td>
				<td>{{  number_format($data['len_produksi']) }}  M</td>
			</tr>
			<tr>	
				<td></td>
				<td>&Sigma; Panjang Jalan Sudah Diperkeras</td>
				<td>{{  number_format($data['len_pavement']) }} M</td>
			</tr>
			<tr>	
				<td></td>
				<td>&Sigma; Panjang Jalan Belum Diperkeras</td>
				<td>{{  number_format($data['len_produksi'] - $data['len_pavement']) }} M</td>
			</tr>

			<tr>
				<th colspan="3">Jalan Non Produksi</th>
			</tr>
			<tr>	
				<td></td>
				<td>Total Jalan Non Produksi</td>
				<td>{{  number_format($data['jum_non_produksi']) }} Unit</td>
			</tr>
			<tr>	
				<td></td>
				<td>&Sigma; Panjang Jalan Non Produksi</td>
				<td>{{  number_format($data['len_non_produksi']) }} M</td>
			</tr>

			<tr>
				<th colspan="3">Jalan Umum</th>
			</tr>
			<tr>	
				<td></td>
				<td>Total Jalan Umum</td>
				<td>{{  number_format($data['jum_umum']) }} Unit</td>
			</tr>
			<tr>	
				<td></td>
				<td>&Sigma; Panjang Jalan Umum</td>
				<td>{{  number_format($data['len_umum']) }} M</td>
			</tr>
		</table>
	</div>
	<div class="col-sm-4">
		<table class="table table-condensed">
			<tr>
				<th colspan="2">Data Perkerasan</th>
			</tr>
			@foreach($data['perkerasan'] as $dp)
   
			<tr>	
				<td>{{ $dp->bulan }}</td>
				<td>{{ number_format($dp->len_perkerasan) }} M</td>
			</tr>
			@endforeach
	</table>

	</div>
</div>

<div class="form-group row">

</div>