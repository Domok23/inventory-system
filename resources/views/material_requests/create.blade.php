@extends('layouts.app')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('material_requests.store') }}">
        @csrf
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label>Material</label>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                    + Quick Add Material
                </button>
            </div>
            <select name="inventory_id" id="inventory_id" class="form-select select2">
                @foreach($inventories as $inv)
                    <option value="{{ $inv->id }}">{{ $inv->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label>Project</label>
                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                    + Quick Add Project
                </button>
            </div>
            <select name="project_id" id="project_id" class="form-select select2">
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" step="0.01" name="qty" class="form-control" required>
        </div>
        <button class="btn btn-success">Submit Request</button>
    </form>
    <!-- Add Material Modal -->
    <div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('inventories.store.quick') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quick Add Material</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                        <label class="mt-2">Quantity</label>
                        <input type="number" step="0.01" name="quantity" class="form-control">
                        <label class="mt-2">Unit</label>
                        <input type="text" name="unit" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Material</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Add Project Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('projects.store.quick') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quick Add Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label>Project Name</label>
                        <input type="text" name="name" class="form-control" required>
                        <label class="mt-2">Qty</label>
                        <input type="number" step="1" name="qty" class="form-control">
                        <label class="mt-2">Department</label>
                        <select name="department" class="form-select" required>
                            <option value="mascot">Mascot</option>
                            <option value="costume">Costume</option>
                            <option value="mascot&costume">Mascot & Costume</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Project</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%',
        placeholder: 'Select an option',
        allowClear: true
    });

    // Modal auto-refresh select2 setelah modal ditutup
    $('#addMaterialModal').on('hidden.bs.modal', function () {
        refreshMaterialSelect();
    });

    $('#addProjectModal').on('hidden.bs.modal', function () {
        refreshProjectSelect();
    });

    function refreshSelect(selector, url) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                let $select = $(selector);
                $select.empty();
                data.forEach(function(item) {
                    $select.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
                $select.trigger('change');
            },
            error: function() {
                alert('Failed to load updated data.');
            }
        });
    }

    function refreshMaterialSelect() {
        refreshSelect('#inventory_id', "{{ route('inventories.json') }}");
    }

    function refreshProjectSelect() {
        refreshSelect('#project_id', "{{ route('projects.json') }}");
    }
});
</script>
@endpush
