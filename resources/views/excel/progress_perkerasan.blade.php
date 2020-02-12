<table>
    <thead>
        <tr style="background-color:#ccc;">
            <th>Company</th>
            <th>Estate</th>
            <th>Afdeling</th>
            <th>Block</th>
            <th>Month</th>
            <th>Year</th>
            <th>Status</th>
            <th>Category</th>
            <th>Segment</th>
            <th>Road Name</th>
            <th>Road Code</th>
            <th>Length</th>
            <th>Pavement Length (m)</th>
            <th>Asset Code</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $data)
        <tr>
            <td>{{ $data->company_name }}</td>
            <td>{{ $data->estate_name }}</td>
            <td>{{ $data->afdeling_code }}</td>
            <td>{{ $data->block_name }}</td>
            <td>{{ $data->month }}</td>
            <td>{{ $data->year }}</td>
            <td>{{ $data->status_name }}</td>
            <td>{{ $data->category_name }}</td>
            <td>{{ $data->segment }}</td>
            <td>{{ $data->road_name }}</td>
            <td>{{ $data->road_code }}</td>
            <td>{{ $data->total_length }}</td>
            <td>{{ $data->length }}</td>
            <td>{{ $data->asset_code }}</td>
        </tr>
    @endforeach
    </tbody>
</table>