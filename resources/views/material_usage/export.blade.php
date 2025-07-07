<table>
    <thead>
        <tr>
            <th>Material</th>
            <th>Used Quantity</th>
            <th>Unit</th>
            <th>Project</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($usages as $usage)
            <tr>
                <td>{{ $usage->inventory->name ?? '(No Material)' }}</td>
                <td>{{ $usage->used_quantity }}</td>
                <td>{{ $usage->inventory->unit ?? '(No Unit)' }}</td>
                <td>{{ $usage->project->name ?? '(No Project)' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
