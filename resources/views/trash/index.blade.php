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

        <form id="bulk-action-form" method="POST" action="{{ route('trash.bulkAction') }}">
            @csrf
            <input type="hidden" name="action" id="bulk-action-type">
            <button type="button" class="btn btn-success btn-sm mb-2" id="bulk-restore-btn">Bulk Restore</button>
            <button type="button" class="btn btn-danger btn-sm mb-2" id="bulk-delete-btn">Bulk Delete Permanently</button>
        </form>

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
                        <th><input type="checkbox" class="select-all"></th>
                        <th>#</th>
                        <th>ID</th>
                        <th>Name/Info</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($$var as $item)
                        <tr>
                            <td>
                                <input type="checkbox" class="select-item" name="selected_ids[]"
                                    value="{{ $item->id }}">
                                <input type="hidden" name="model_map[{{ $item->id }}]"
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
                            </td>
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
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            @foreach (['inventories', 'goodsIns', 'goodsOuts', 'projects', 'users', 'materialUsages', 'materialRequests', 'categories', 'currencies'] as $var)
                $('#table-{{ $var }}').DataTable({
                    responsive: true,
                    order: [],
                    pageLength: 10
                });
            @endforeach

            // Bulk action scripts
            let selectedIds = [];
            $('.select-item').on('change', function() {
                const id = $(this).val();
                if ($(this).is(':checked')) {
                    selectedIds.push(id);
                } else {
                    selectedIds = selectedIds.filter(item => item !== id);
                }
                $('#bulk-action-form').toggle(selectedIds.length > 0);
            });

            $('#bulk-restore-btn').on('click', function() {
                if (selectedIds.length === 0) return;
                if (confirm('Restore selected items?')) {
                    $('#bulk-action-type').val('restore');
                    $('#bulk-action-form').submit();
                }
            });

            $('#bulk-delete-btn').on('click', function() {
                if (selectedIds.length === 0) return;
                if (confirm('Delete permanently?')) {
                    $('#bulk-action-type').val('delete');
                    $('#bulk-action-form').submit();
                }
            });
        });
    </script>
@endpush
