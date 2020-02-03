<table>
    <thead>
        <tr style="background-color:#ccc;">
            <th>Road Code</th>
            <th>Road Name</th>
            <th>Length</th>
            <th>Progress (m)</th>
            <th>Progress (%)</th>
            <th>Asset Code</th>
            <th>Segment</th>
            <th>Status</th>
            <th>Category</th>
            <th>Company</th>
            <th>Estate</th>
            <th>Afdeling</th>
            <th>Block</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $data)
        <tr>
            <td>{{ $data->road_code }}</td>
            <td>{{ $data->road_name }}</td>
            <td>{{ $data->total_length }}</td>
            <td>{{ $data->curr_progress }}</td>
            <td>{{ $data->progress }}</td>
            <td>{{ $data->asset_code }}</td>
            <td>{{ $data->segment }}</td>
            <td>{{ $data->status_name }}</td>
            <td>{{ $data->category_name }}</td>
            <td>{{ $data->company_name }}</td>
            <td>{{ $data->estate_name }}</td>
            <td>{{ $data->afdeling_code }}</td>
            <td>{{ $data->block_name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>