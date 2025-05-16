@extends('layouts.app')
@section('content')
    <div class="container">
        <h3>Material Usage</h3>
        <table class="table table-bordered table-hover" id="datatable">
            <thead>
                <tr>
                    <th style="width: 20px;" class="text-center align-middle">#</th>
                    <th>Material</th>
                    <th>Project</th>
                    <th>Used Quantity</th>
                    @if (auth()->user()->role === 'super_admin')
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($usages as $usage)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $usage->inventory->name }}</td>
                        <td class="align-middle">{{ $usage->project->name }}</td>
                        <td class="align-middle" style="font-weight: bold;">{{ $usage->used_quantity }}
                            {{ $usage->inventory->unit }}</td>
                        @if (auth()->user()->role === 'super_admin')
                            <td>
                                <form action="{{ route('material_usage.destroy', $usage->id) }}" method="POST"
                                    style="display:inline;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete">Delete</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        });
    </script>
@endpush
