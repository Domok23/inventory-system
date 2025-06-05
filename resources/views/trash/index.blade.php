@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <h3 class="mb-0 flex-shrink-0" style="font-size:1.5rem;">Trash Bin</h3>
                <hr>
                <form id="bulk-action-form" method="POST" action="{{ route('trash.bulkAction') }}">
                    @csrf
                    <input type="hidden" name="action" id="bulk-action-type">
                    <button type="button" class="btn btn-success btn-sm mb-2" id="bulk-restore-btn">Bulk Restore</button>
                    <button type="button" class="btn btn-danger btn-sm mb-2" id="bulk-delete-btn">Bulk Delete
                        Permanently</button>
                </form>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Trash Tables -->
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
                    <h5 class="mt-4">{{ $label }}</h5>
                    <table class="table table-bordered table-sm align-middle" id="table-{{ $var }}">
                        <thead class="table-light align-middle">
                            <tr>
                                <th><input type="checkbox" class="select-all"></th>
                                <th>ID</th>
                                <th>Name/Info</th>
                                <th>Deleted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
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
                                        <div class="d-flex flex-wrap gap-1">
                                            <form action="{{ route('trash.restore') }}" method="POST"
                                                class="restore-form">
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
                                                <button class="btn btn-success btn-sm restore-btn"
                                                    type="button">Restore</button>
                                            </form>
                                            <form action="{{ route('trash.forceDelete') }}" method="POST"
                                                class="delete-form">
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
                                                <button class="btn btn-danger btn-sm delete-btn" type="button">Delete
                                                    Permanently</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
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

        $(document).ready(function() {
            // SweetAlert2 for Restore
            $('.restore-btn').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to restore this item.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore it!',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // SweetAlert2 for Delete Permanently
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone. The item will be permanently deleted.",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
