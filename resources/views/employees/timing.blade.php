@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Timing {{ $employee->name }}</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Project</th>
                <th>Category</th>
                <th>Step</th>
                <th>Parts</th>
                <th>Start</th>
                <th>End</th>
                <th>Qty</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($timings as $timing)
            <tr>
                <td>{{ $timing->tanggal }}</td>
                <td>{{ $timing->project->name ?? '-' }}</td>
                <td>{{ $timing->category }}</td>
                <td>{{ $timing->step }}</td>
                <td>{{ $timing->parts }}</td>
                <td>{{ $timing->start_time }}</td>
                <td>{{ $timing->end_time }}</td>
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
            @endforeach
        </tbody>
    </table>
</div>
@endsection
