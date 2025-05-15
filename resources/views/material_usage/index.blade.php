@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Material Usage</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Material</th>
                <th>Project</th>
                <th>Used Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usages as $usage)
                <tr>
                    <td>{{ $usage->inventory->name }}</td>
                    <td>{{ $usage->project->name }}</td>
                    <td>{{ $usage->used_quantity }} {{ $usage->inventory->unit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
