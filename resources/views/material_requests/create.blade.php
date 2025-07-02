@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.3rem;">Create Material Request</h2>
                <hr>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form method="POST" action="{{ route('material_requests.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label>Project <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                    data-bs-target="#addProjectModal">
                                    + Quick Add Project
                                </button>
                            </div>
                            <select name="project_id" id="project_id" class="form-select select2" required>
                                <option value="">Select an option</option>
                                @foreach ($projects as $proj)
                                    <option value="{{ $proj->id }}" data-department="{{ $proj->department }}"
                                        {{ old('project_id') == $proj->id ? 'selected' : '' }}>
                                        {{ $proj->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="department" class="form-text d-none">Department</div>
                            @error('project_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label>Material <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#addMaterialModal">
                                    + Quick Add Material
                                </button>
                            </div>
                            <select name="inventory_id" id="inventory_id" class="form-select select2" required>
                                <option value="">Select an option</option>
                                @foreach ($inventories as $inv)
                                    <option value="{{ $inv->id }}" data-unit="{{ $inv->unit }}"
                                        data-stock="{{ $inv->quantity }}"
                                        {{ old('inventory_id') == $inv->id ? 'selected' : '' }}>
                                        {{ $inv->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="available-qty" class="form-text d-none"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label>Requested Quantity <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="qty" class="form-control" step="any" required
                                    value="{{ old('qty') }}">
                                <span class="input-group-text unit-label">unit</span>
                            </div>
                            @error('qty')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>Remark (Optional)</label>
                            <textarea name="remark" class="form-control">{{ old('remark') }}</textarea>
                            @error('remark')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <button class="btn btn-success">Submit Request</button>
                </form>
                <!-- Add Material Modal -->
                <div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel"
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
                                    <label>Material Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                    <label class="mt-2">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" step="any" name="quantity" class="form-control" required>
                                    <label class="mt-2">Unit <span class="text-danger">*</span></label>
                                    <input type="text" name="unit" class="form-control" required>
                                    <label class="mt-2">Remark (optional)</label>
                                    <textarea name="remark" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Add Material</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Add Project Modal -->
                <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel"
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
                                    <label class="mt-2">Quantity <span class="text-danger">*</span></label>
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
    </div>
@endsection
@push('styles')
    <style>
        .select2-container .select2-selection--single {
            height: calc(2.375rem + 2px);
            /* Tinggi elemen form Bootstrap */
            padding: 0.375rem 0.75rem;
            /* Padding elemen form Bootstrap */
            font-size: 1rem;
            /* Ukuran font Bootstrap */
            line-height: 1.5;
            /* Line height Bootstrap */
            border: 1px solid #ced4da;
            /* Border Bootstrap */
            border-radius: 0.375rem;
            /* Border radius Bootstrap */
        }

        .select2-selection__rendered {
            line-height: 1.5;
            /* Line height Bootstrap */
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            height: calc(2.375rem + 2px);
            /* Tinggi elemen form Bootstrap */
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select an option',
                allowClear: true,
                theme: 'bootstrap-5',
            }).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field')
                        .focus();
                }, 100);
            });

            // Update unit label dynamically when material is selected
            $('select[name="inventory_id"]').on('change', function() {
                const selectedUnit = $(this).find(':selected').data('unit');
                $('.unit-label').text(selectedUnit || 'unit');
            });
            // Trigger saat halaman load jika sudah ada value terpilih
            $('select[name="inventory_id"]').trigger('change');
        });

        // Form submit handler
        $(document).ready(function() {
            // Quick Add Project
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
                            let newOption = new Option(res.project.name, res.project.id, true,
                                true);
                            $('select[name="project_id"]').append(newOption).val(res.project.id)
                                .trigger('change');
                            $('#addProjectModal').modal('hide');
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

            // Quick Add Material
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
                            let newOption = new Option(res.material.name, res.material.id, true,
                                true);
                            $('select[name="inventory_id"]').append(newOption).val(res.material
                                .id).trigger('change');
                            $('#addMaterialModal').modal('hide');
                            form[0].reset();
                        } else {
                            alert('Failed to add material!');
                        }
                    },
                    error: function() {
                        alert('Failed to add material. Material already exist.');
                    }
                });
            });
        });

        // Update available quantity and unit label when inventory is selected
        $('select[name="inventory_id"]').on('change', function() {
            const selected = $(this).find(':selected');
            const selectedUnit = selected.data('unit');
            const selectedStock = selected.data('stock');
            $('.unit-label').text(selectedUnit || 'unit');

            const $availableQty = $('#available-qty');
            $availableQty.removeClass('d-none text-danger text-warning');

            if (selected.val() && selectedStock !== undefined) {
                let colorClass = '';
                if (selectedStock == 0) {
                    colorClass = 'text-danger';
                } else if (selectedStock < 3) {
                    colorClass = 'text-warning';
                }
                $availableQty
                    .text(`Available Qty: ${selectedStock} ${selectedUnit || ''}`)
                    .addClass(colorClass);
            } else {
                $availableQty.addClass('d-none').text('');
            }
        });
        // Trigger saat halaman load jika sudah ada value terpilih
        $('select[name="inventory_id"]').trigger('change');

        // Update department text when project is selected
        $('select[name="project_id"]').on('change', function() {
            const selected = $(this).find(':selected');
            const department = selected.data('department');
            const $departmentDiv = $('#department');
            $departmentDiv.removeClass('d-none text-danger text-warning');

            if (selected.val() && department) {
                $departmentDiv.text(`Department: ${department.charAt(0).toUpperCase() + department.slice(1)}`);
            } else {
                $departmentDiv.addClass('d-none').text('Department');
            }
        });
        // Trigger saat halaman load jika sudah ada value terpilih
        $('select[name="project_id"]').trigger('change');
    </script>
@endpush
