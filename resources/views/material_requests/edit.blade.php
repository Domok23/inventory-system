@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Edit Material Request</h3>
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
        <form action="{{ route('material_requests.update', $request->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label>Material</label>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#addMaterialModal">
                        + Quick Add Material
                    </button>
                </div>
                <select name="inventory_id" class="form-select select2" required>
                    @foreach ($inventories as $inv)
                        <option value="{{ $inv->id }}" {{ $inv->id == $request->inventory_id ? 'selected' : '' }}>
                            {{ $inv->name }}
                        </option>
                    @endforeach
                </select>
                @error('inventory_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label>Project</label>
                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                        data-bs-target="#addProjectModal">
                        + Quick Add Project
                    </button>
                </div>
                <select name="project_id" class="form-select select2" required>
                    @foreach ($projects as $proj)
                        <option value="{{ $proj->id }}" {{ $proj->id == $request->project_id ? 'selected' : '' }}>
                            {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label>Quantity</label>
                <input type="number" step="0.01" name="qty" class="form-control" value="{{ $request->qty }}"
                    required>
                @error('qty')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            @if (in_array(auth()->user()->role, ['admin_logistic', 'super_admin']))
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $request->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $request->status === 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                </div>
            @endif

            <div class="mb-3">
                <label>Remark</label>
                <textarea name="remark" class="form-control" rows="1">{{ $request->remark }}</textarea>
                @error('remark')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label>Requested By</label>
                <input type="text" class="form-control" value="{{ $request->requested_by }}" disabled>
            </div>

            <div class="mb-3">
                <label>Department</label>
                <input type="text" class="form-control" value="{{ ucfirst($request->department) }}" disabled>
            </div>

            <button type="submit" class="btn btn-primary">Update Request</button>
            <a href="{{ route('material_requests.index') }}" class="btn btn-secondary">Back</a>
        </form>

        <!-- Add Material Modal -->
        <div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel"
            aria-hidden="true">
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
        <div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('projects.store.quick') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Quick Add Project</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
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
                theme: 'bootstrap-5'
            }).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-container--open .select2-search__field')
                        .focus();
                }, 100);
            });

            $('#addMaterialModal').on('hidden.bs.modal', function() {
                refreshMaterialSelect();
            });

            $('#addProjectModal').on('hidden.bs.modal', function() {
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
                            $select.append('<option value="' + item.id + '">' + item.name +
                                '</option>');
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
