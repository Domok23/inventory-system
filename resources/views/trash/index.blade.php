@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Trash Bin</h2>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @foreach ([
            'inventories' => 'Inventory',
            'goodsIns' => 'Goods In',
            'goodsOuts' => 'Goods Out',
            'projects' => 'Project',
            'users' => 'User',
            'materialUsages' => 'Material Usage',
            'materialRequests' => 'Material Request',
            'currencies' => 'Currency',
        ] as $var => $label)
            <h4 class="mt-4">{{ $label }}</h4>
            <table class="table table-bordered table-sm align-middle" id="table-{{ $var }}">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Name/Info</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($$var as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->id }}</td>
                            <td>
                                @if (isset($item->name))
                                    {{ $item->name }}
                                @elseif(isset($item->username))
                                    {{ $item->username }}
                                @elseif(isset($item->remark))
                                    {{ $item->remark }}
                                @else
                                    (no info)
                                @endif
                            </td>
                            <td>{{ $item->deleted_at }}</td>
                            <td>
                                <form action="{{ route('trash.restore') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="model"
                                        value="{{ [
                                            'inventories' => 'inventory',
                                            'goodsIns' => 'goods_in',
                                            'goodsOuts' => 'goods_out',
                                            'projects' => 'project',
                                            'users' => 'user',
                                            'materialUsages' => 'material_usage',
                                            'materialRequests' => 'material_request',
                                            'currencies' => 'currency',
                                        ][$var] }}">
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <button class="btn btn-success btn-sm" type="submit">Restore</button>
                                </form>
                                <form action="{{ route('trash.forceDelete') }}" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Delete permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="model"
                                        value="{{ [
                                            'inventories' => 'inventory',
                                            'goodsIns' => 'goods_in',
                                            'goodsOuts' => 'goods_out',
                                            'projects' => 'project',
                                            'users' => 'user',
                                            'materialUsages' => 'material_usage',
                                            'materialRequests' => 'material_request',
                                            'currencies' => 'currency',
                                        ][$var] }}">
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <button class="btn btn-danger btn-sm" type="submit">Delete Permanently</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No deleted {{ $label }} data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endforeach
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            @foreach ([
                'inventories',
                'goodsIns',
                'goodsOuts',
                'projects',
                'users',
                'materialUsages',
                'materialRequests',
                'categories',
                'currencies'
            ] as $var)
                $('#table-{{ $var }}').DataTable({
                    responsive: true,
                    order: [],
                    pageLength: 10
                });
            @endforeach
        });
    </script>
@endpush
