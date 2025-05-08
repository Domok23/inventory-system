@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Bulk Material Request</h4>

    <div class="mb-3">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterialModal">+ Quick Add Material</button>
        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addProjectModal">+ Quick Add Project</button>
    </div>

    <form method="POST" action="{{ route('material_requests.bulk_store') }}">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="bulk-material-table" style="min-width: 1000px;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 25%;">Project</th>
                        <th style="width: 25%;">Material</th>
                        <th style="width: 15%;">Qty</th>
                        <th style="width: 25%;">Remark (ops)</th>
                        <th style="width: 10%;">Action</th>
                    </tr>
                </thead>
                <tbody id="bulk-rows">
                    @foreach(range(0, 0) as $index)
                        <tr>
                            <td>
                                <select name="requests[{{ $index }}][project_id]" class="form-select select2 project-select" required>
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="requests[{{ $index }}][inventory_id]" class="form-select select2 material-select" required>
                                    <option value="">Select Material</option>
                                    @foreach($inventories as $inv)
                                        <option value="{{ $inv->id }}" data-unit="{{ $inv->unit }}">{{ $inv->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="number" name="requests[{{ $index }}][qty]" class="form-control" step="0.01" required>
                                    <span class="input-group-text unit-label">unit</span>
                                </div>
                            </td>
                            <td>
                                <textarea name="requests[{{ $index }}][remark]" class="form-control" rows="1"></textarea>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                            </td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <button type="button" class="btn btn-outline-primary" id="add-row">+ Add Row</button>
            <button type="submit" class="btn btn-success">Submit All</button>
        </div>
    </form>
</div>

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
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: calc(2.375rem + 2px);
            padding: 0.375rem 0.75rem;
        }
        .select2-selection__rendered {
            line-height: 1.5;
        }
        .unit-label {
            min-width: 50px;
        }
    </style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function initSelect2(row) {
        row.find('.select2').select2({
            width: '100%',
            theme: 'bootstrap-5',
            dropdownAutoWidth: true,
            placeholder: 'Select...'
        });

        row.find('.material-select').on('change', function() {
            const unit = $(this).find(':selected').data('unit') || 'unit';
            row.find('.unit-label').text(unit);
        });
    }

    $(document).ready(function() {
        initSelect2($('#bulk-rows tr').first());

        $('#add-row').click(function() {
            let newRow = $('#bulk-rows tr').first().clone();
            let rowCount = $('#bulk-rows tr').length;

            // Reset select elements
            newRow.find('select').each(function() {
                let name = $(this).attr('name').replace(/\d+/, rowCount);
                $(this).attr('name', name);

                // Jika ini adalah select project, salin nilai yang dipilih
                if ($(this).hasClass('project-select')) {
                    let selectedValue = $('#bulk-rows tr').first().find('.project-select').val(); // Ambil nilai dari baris pertama
                    $(this).val(selectedValue).trigger('change'); // Tetapkan nilai yang sama di baris baru
                } else {
                    $(this).val(null).trigger('change'); // Reset untuk select lainnya
                }
            });

            // Reset input elements
            newRow.find('input').each(function() {
                let name = $(this).attr('name').replace(/\d+/, rowCount);
                $(this).attr('name', name).val('');
            });

            // Reset textarea elements (e.g., Remark)
            newRow.find('textarea').each(function() {
                let name = $(this).attr('name').replace(/\d+/, rowCount);
                $(this).attr('name', name).val(''); // Reset value to empty
            });

            // Reset unit label
            newRow.find('.unit-label').text('unit');

            // Append the new row
            $('#bulk-rows').append(newRow);
            initSelect2(newRow); // Reinitialize Select2 for the new row
        });

        $(document).on('click', '.remove-row', function() {
            if ($('#bulk-rows tr').length > 1) {
                $(this).closest('tr').remove();
            }
        });
    });
</script>
@endpush
