<table>
    <thead>
        <tr>
            <th>Project</th>
            <th>Material</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Requested By</th>
            <th>Requested At</th>
            <th>Status</th>
            <th>Remark</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requests as $req)
            <tr>
                <td>{{ $req->project->name ?? '-' }}</td>
                <td>{{ $req->inventory->name ?? '-' }}</td>
                <td>{{ $req->qty }}</td>
                <td>{{ $req->inventory->unit ?? '-' }}</td>
                <td>{{ ucfirst($req->requested_by) }}</td>
                <td>{{ $req->created_at->format('d-m-Y, H:i') }}</td>
                <td>{{ ucfirst($req->status) }}</td>
                <td>{{ $req->remark }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
