<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Quantity</th>
            <th>Department</th>
            <th>Deadline</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($projects as $project)
            <tr>
                <td>{{ $project->name }}</td>
                <td>{{ $project->qty }}</td>
                <td>{{ ucwords(str_replace('&', ' & ', $project->department)) }}</td>
                <td>{{ \Carbon\Carbon::parse($project->deadline)->translatedFormat('d F Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
