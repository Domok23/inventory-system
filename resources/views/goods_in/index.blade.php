@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 mb-3">
                    <!-- Header -->
                    <h2 class="mb-2 mb-md-0 flex-shrink-0" style="font-size:1.5rem;"><i class="bi bi-arrow-down-circle"></i> Goods In
                        Records</h2>

                    <!-- Spacer untuk mendorong tombol ke kanan -->
                    <div class="ms-md-auto d-flex flex-wrap gap-2">
                        <a href="{{ route('goods_in.create_independent') }}" class="btn btn-primary btn-sm flex-shrink-0">
                            + New Goods In
                        </a>
                        <a href="{{ route('goods_in.export', request()->query()) }}"
                            class="btn btn-success btn-sm flex-shrink-0">
                            Export to Excel
                        </a>
                    </div>
                </div>

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

                <div class="mb-3">
                    <form id="filter-form" method="GET" action="{{ route('goods_in.index') }}" class="row g-2">
                        <div class="col-md-2">
                            <select id="filter-material" name="material" class="form-select select2">
                                <option value="">All Materials</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}" {{ request('material') == $material->id ? 'selected' : '' }}>
                                        {{ $material->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filter-project" name="project" class="form-select select2">
                                <option value="">All Projects</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filter-qty" name="qty" class="form-select select2">
                                <option value="">All Quantities</option>
                                @foreach ($quantities as $qty)
                                    <option value="{{ $qty }}" {{ request('qty') == $qty ? 'selected' : '' }}>
                                        {{ $qty }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filter-returned-by" name="returned_by" class="form-select select2">
                                <option value="">All Returned By</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->username }}" {{ request('returned_by') == $user->username ? 'selected' : '' }}>
                                        {{ ucfirst($user->username) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" id="filter-returned-at" name="returned_at" class="form-control"
                                value="{{ request('returned_at') }}" placeholder="Returned At Date">
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('goods_in.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <table class="table table-hover table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Material</th>
                            <th>Qty Returned/In</th>
                            <th>Project</th>
                            <th>Returned/In By</th>
                            <th>Returned/In At</th>
                            <th>Remark</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($goodsIns as $goodsIn)
                            <tr>
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">
                                    @if ($goodsIn->goodsOut && $goodsIn->goodsOut->inventory)
                                        {{ $goodsIn->goodsOut->inventory->name }}
                                    @elseif($goodsIn->inventory)
                                        {{ $goodsIn->inventory->name }}
                                    @else
                                        <span class="text-danger">-</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if ($goodsIn->goodsOut && $goodsIn->goodsOut->inventory)
                                        {{ $goodsIn->quantity }} {{ $goodsIn->goodsOut->inventory->unit }}
                                    @elseif($goodsIn->inventory)
                                        {{ $goodsIn->quantity }} {{ $goodsIn->inventory->unit }}
                                    @else
                                        {{ $goodsIn->quantity }}
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if ($goodsIn->goodsOut && $goodsIn->goodsOut->project)
                                        {{ $goodsIn->goodsOut->project->name }}
                                    @elseif($goodsIn->project)
                                        {{ $goodsIn->project->name }}
                                    @else
                                        <span class="text-secondary">No Project (Restock/Supplier)</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ ucfirst($goodsIn->returned_by) }}</td>
                                <td class="align-middle">
                                    {{ \Carbon\Carbon::parse($goodsIn->returned_at)->format('d-m-Y, H:i') }}
                                </td>
                                <td class="align-middle">
                                    @if ($goodsIn->remark)
                                        {{ $goodsIn->remark }}
                                    @else
                                        <span class="text-danger">-</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('goods_in.edit', $goodsIn->id) }}"
                                            class="btn btn-sm btn-warning">Edit</a>
                                        @if (!$goodsIn->goods_out_id)
                                            <form action="{{ route('goods_in.destroy', $goodsIn->id) }}" method="POST"
                                                class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-delete">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true
            });

            // SweetAlert for delete confirmation
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true
            });

            // Add placeholder support for input[type="date"]
            const dateInput = document.getElementById('filter-returned-at');
            if (dateInput) {
                dateInput.onfocus = function() {
                    this.type = 'date';
                };
                dateInput.onblur = function() {
                    if (!this.value) this.type = 'text';
                };
                if (!dateInput.value) dateInput.type = 'text';
            }
        });
    </script>
@endpush
