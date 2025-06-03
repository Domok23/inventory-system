@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h4 class="">Bulk Material Request</h4>
                <p class="text-muted mb-3">Use this form to create multiple material requests at once. You can add multiple
                    rows for different projects and materials.</p>
                <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                        data-bs-target="#quickAddProjectModal">+
                        Quick Add Project</button>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#quickAddMaterialModal">+
                        Quick Add Material</button>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('material_requests.bulk_store') }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="bulk-material-table"
                            style="min-width: 1000px;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 25%;">Project <span class="text-danger">*</span></th>
                                    <th style="width: 25%;">Material <span class="text-danger">*</span></th>
                                    <th style="width: 15%;">Quantity <span class="text-danger">*</span></th>
                                    <th style="width: 25%;">Remark (optional)</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="bulk-rows">
                                @foreach (old('requests', [0 => []]) as $index => $request)
                                    <tr>
                                        <td>
                                            <select name="requests[{{ $index }}][project_id]"
                                                class="form-select select2 project-select" required>
                                                <option value="">Select Project</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}"
                                                        {{ old("requests.$index.project_id") == $project->id ? 'selected' : '' }}>
                                                        {{ $project->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("requests.$index.project_id")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <select name="requests[{{ $index }}][inventory_id]"
                                                class="form-select select2 material-select" required>
                                                <option value="">Select Material</option>
                                                @foreach ($inventories as $inventory)
                                                    <option value="{{ $inventory->id }}" data-unit="{{ $inventory->unit }}"
                                                        {{ old("requests.$index.inventory_id") == $inventory->id ? 'selected' : '' }}>
                                                        {{ $inventory->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("requests.$index.inventory_id")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="requests[{{ $index }}][qty]"
                                                    class="form-control" step="any"
                                                    value="{{ old("requests.$index.qty") }}" required>
                                                <span class="input-group-text unit-label">unit</span>
                                            </div>
                                            @error("requests.$index.qty")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <textarea name="requests[{{ $index }}][remark]" class="form-control" rows="1">{{ old("requests.$index.remark") }}</textarea>
                                            @error("requests.$index.remark")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
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
            <div class="modal fade" id="quickAddMaterialModal" tabindex="-1" aria-labelledby="quickAddMaterialModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form id="quickAddMaterialForm" method="POST" action="{{ route('inventories.store.quick') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Quick Add Material</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                                <label class="mt-2">Quantity <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="quantity" class="form-control" required>
                                <label class="mt-2">Unit <span class="text-danger">*</span></label>
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
            <div class="modal fade" id="quickAddProjectModal" tabindex="-1" aria-labelledby="quickAddProjectModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form id="quickAddProjectForm" method="POST" action="{{ route('projects.store.quick') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Quick Add Project</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label>Project Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                                <label class="mt-2">Qty <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="qty" class="form-control" required>
                                <label class="mt-2">Department <span class="text-danger">*</span></label>
                                <select name="department" class="form-select" required>
                                    <option value="mascot">Mascot</option>
                                    <option value="costume">Costume</option>
                                    <option value="mascot&costume">Mascot & Costume</option>
                                    <option value="animatronic">Animatronic</option>
                                    <option value="plustoys">Plus Toys</option>
                                    <option value="it">IT</option>
                                    <option value="facility">Facility</option>
                                    <option value="bag">Bag</option>
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
    </div>
@endsection

@push('styles')
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
    <script>
        function initSelect2(row) {
            row.find('.select2').select2({
                width: '100%',
                theme: 'bootstrap-5',
                dropdownAutoWidth: true,
                placeholder: 'Select...'
            }).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field')
                        .focus();
                }, 100);
            });

            row.find('.material-select').on('change', function() {
                const unit = $(this).find(':selected').data('unit') || 'unit';
                row.find('.unit-label').text(unit);
            });
        }

        $(document).ready(function() {
            initSelect2($('#bulk-rows tr').first());

            $('#add-row').click(function() {
                let lastRow = $('#bulk-rows tr').last(); // Ambil baris terakhir

                // Check if Select2 is initialized before destroying
                lastRow.find('.select2').each(function() {
                    if ($(this).data('select2')) {
                        $(this).select2('destroy');
                    }
                });

                // Clone the last row
                let newRow = lastRow.clone();
                let rowCount = $('#bulk-rows tr').length;

                // Reset select elements
                newRow.find('select').each(function() {
                    let name = $(this).attr('name').replace(/\d+/, rowCount);
                    $(this).attr('name', name);

                    // If it's the project-select, copy the value from the last row
                    if ($(this).hasClass('project-select')) {
                        let previousValue = lastRow.find('.project-select').val();
                        $(this).val(previousValue).trigger('change');
                    } else {
                        // Clear the value for other select elements
                        $(this).val('').trigger('change');
                    }
                });

                // Reset input elements
                newRow.find('input').each(function() {
                    let name = $(this).attr('name').replace(/\d+/, rowCount);
                    $(this).attr('name', name).val('');
                });

                // Reset textarea elements
                newRow.find('textarea').each(function() {
                    let name = $(this).attr('name').replace(/\d+/, rowCount);
                    $(this).attr('name', name).val('');
                });

                // Reset unit label
                newRow.find('.unit-label').text('unit');

                // Append the new row
                $('#bulk-rows').append(newRow);

                // Reinitialize Select2 for the new row
                initSelect2(newRow);

                // Reinitialize Select2 for the last row
                initSelect2(lastRow);
            });

            $(document).on('click', '.remove-row', function() {
                if ($('#bulk-rows tr').length > 1) {
                    $(this).closest('tr').remove();
                }
            });
        });

        $(document).ready(function() {
            // Update unit label dynamically when material is selected
            $(document).on('change', '.material-select', function() {
                const unit = $(this).find(':selected').data('unit') || 'unit';
                $(this).closest('tr').find('.unit-label').text(unit);
            });

            // Trigger change event on page load to restore old values
            $('.material-select').trigger('change');
        });

        $(document).ready(function() {
            // Quick Add Project (Bulk)
            $('#quickAddProjectForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success && res.project) {
                            // Tambahkan ke semua select2 project di setiap row
                            $('.project-select').each(function() {
                                let exists = $(this).find('option[value="' + res.project
                                    .id + '"]').length;
                                if (!exists) {
                                    let newOption = new Option(res.project.name, res
                                        .project.id, false, false);
                                    $(this).append(newOption);
                                }
                            });
                            // Pilih otomatis di row terakhir
                            $('.project-select').last().val(res.project.id).trigger('change');
                            $('#quickAddProjectForm').closest('.modal').modal('hide');
                            form[0].reset();
                        } else {
                            alert('Failed to add project');
                        }
                    },
                    error: function() {
                        alert('Failed to add project');
                    }
                });
            });

            // Quick Add Material (Bulk)
            $('#quickAddMaterialForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success && res.material) {
                            $('.material-select').each(function() {
                                let exists = $(this).find('option[value="' + res
                                    .material.id + '"]').length;
                                if (!exists) {
                                    let newOption = new Option(res.material.name, res
                                        .material.id, false, false);
                                    $(this).append(newOption);
                                }
                            });
                            $('.material-select').last().val(res.material.id).trigger('change');
                            $('#quickAddMaterialForm').closest('.modal').modal('hide');
                            form[0].reset();
                        } else {
                            alert('Failed to add material');
                        }
                    },
                    error: function() {
                        alert('Failed to add material');
                    }
                });
            });
        });
    </script>
@endpush
