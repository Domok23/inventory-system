@forelse($timings as $timing)
    <tr>
        <td>{{ $timing->tanggal }}</td>
        <td>{{ $timing->project->name ?? '-' }}</td>
        <td>{{ $timing->project->department->name }}</td>
        <td>{{ $timing->step }}</td>
        <td>{{ $timing->parts }}</td>
        <td>{{ $timing->employee->name ?? '-' }}</td>
        <td>{{ \Carbon\Carbon::parse($timing->start_time)->format('H:i') }}</td>
        <td>{{ \Carbon\Carbon::parse($timing->end_time)->format('H:i') }}</td>
        <td>{{ $timing->output_qty }}</td>
        <td>
            @php
                $color = [
                    'pending' => 'danger',
                    'complete' => 'success',
                    'on progress' => 'warning',
                ][$timing->status];
            @endphp
            <span class="badge bg-{{ $color }}">{{ ucfirst($timing->status) }}</span>
        </td>
        <td>{{ $timing->remarks }}</td>
    </tr>
@empty
    <tr class="no-data-row">
        <td colspan="11" class="text-center text-muted py-4">
            No timing data found.
        </td>
    </tr>
@endforelse
