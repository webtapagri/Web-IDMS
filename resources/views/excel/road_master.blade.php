<table>
    <thead>
        <tr style="background-color:#ccc;">
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
    <tbody>
    @foreach($data as $data)
        <tr>
            <td>{{ $data->estate_name }}</td>
            <td>{{ $data->werks }}</td>
            <td>{{ $data->afdeling_code }}</td>
            <td>{{ $data->block_code }}</td>
            <td>{{ $data->block_name }}</td>
            <td>{{ $data->status_name }}</td>
            <td>{{ $data->category_name }}</td>
            <td>{{ $data->segment }}</td>
            <td>{{ $data->road_name }}</td>
            <td>{{ $data->road_code }}</td>
            <td>{{ $data->total_length }}</td>
            <td>{{ $data->asset_code }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
