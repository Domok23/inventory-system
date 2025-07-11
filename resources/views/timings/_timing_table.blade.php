@forelse($timings as $timing)
<tr>
    <td>{{ $timing->tanggal }}</td>
    <td>{{ $timing->project->name ?? '-' }}</td>
    <td>{{ $timing->category }}</td>
    <td>{{ $timing->step }}</td>
    <td>{{ $timing->parts }}</td>
    <td>{{ $timing->employee->name ?? '-' }}</td>
    <td>{{ $timing->start_time }}</td>
    <td>{{ $timing->end_time }}</td>
    <td>{{ $timing->output_qty }}</td>
    <td>
        @php
            $color = [
                'complete' => 'success',
                'on progress' => 'warning',
                'not started' => 'secondary'
            ][$timing->status];
        @endphp
        <span class="badge bg-{{ $color }}">{{ ucfirst($timing->status) }}</span>
    </td>
    <td>{{ $timing->remarks }}</td>
</tr>
@empty
<tr>
    <td colspan="12" class="text-center text-muted">No timing data found.</td>
</tr>
@endforelse