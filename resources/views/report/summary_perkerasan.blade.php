<style>
.table td, .table th {
    border: none;
}

table.table.table-condensed {
    border: 1px solid black;
}
</style>

<div class="form-group row">
	<div class="col-sm-7">
		<table class="table table-condensed">
			<tr>
				<th colspan="4">Jalan Produksi</th>
			</tr>
			<tr>	
				<td></td>
				<td>Total Jalan Produksi</td>
				<td align="right">{{  number_format($data['jum_produksi']) }}</td> <td>Unit</td>
			</tr>
			<tr>	
				<td></td>
				<td>&Sigma; Panjang Jalan Produksi</td>
				<td align="right">{{  number_format($data['len_produksi']) }}</td> <td>  M</td>
			</tr>
			<tr>	
				<td></td>
				<td>&Sigma; Panjang Jalan Sudah Diperkeras</td>
				<td align="right">{{  number_format($data['len_pavement']) }}</td> <td> M</td>
			</tr>
			<tr>	
				<td></td>
				<td>&Sigma; Panjang Jalan Belum Diperkeras</td>
				<td align="right">{{  number_format($data['len_produksi'] - $data['len_pavement']) }}</td> <td> M</td>
			</tr>

			<tr>
				<th colspan="4">Jalan Non Produksi</th>
			</tr>
			<tr>	
				<td></td>
				<td>Total Jalan Non Produksi</td>
				<td align="right">{{  number_format($data['jum_non_produksi']) }}</td> <td> Unit</td>
			</tr>
			<tr>	
				<td></td>
				<td>&Sigma; Panjang Jalan Non Produksi</td>
				<td align="right">{{  number_format($data['len_non_produksi']) }}</td> <td> M</td>
			</tr>

			<tr>
				<th colspan="4">Jalan Umum</th>
			</tr>
			<tr>	
				<td></td>
				<td>Total Jalan Umum</td>
				<td align="right">{{  number_format($data['jum_umum']) }}</td> <td> Unit</td>
			</tr>
			<tr>	
				<td></td>
				<td>&Sigma; Panjang Jalan Umum</td>
				<td align="right">{{  number_format($data['len_umum']) }}</td> <td> M</td>
			</tr>
		</table>
	</div>
	<div class="col-sm-4">
		<table class="table table-condensed">
			<tr>
				<th colspan="3">Data Perkerasan</th>
			</tr>
			@foreach($data['perkerasan'] as $dp)
   
			<tr>	
				<td>{{ $dp->bulan }}</td>
				<td align="right">{{ number_format($dp->len_perkerasan) }}</td> <td> M</td>
			</tr>
			@endforeach
	</table>

	</div>
</div>

<div class="form-group row">

</div>