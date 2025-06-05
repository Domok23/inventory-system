@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 mb-3">
                    <!-- Header -->
                    <h2 class="mb-2 mb-md-0 flex-shrink-0" style="font-size:1.5rem;">Projects</h2>

                    <!-- Spacer untuk mendorong tombol ke kanan -->
                    <div class="ms-md-auto d-flex flex-wrap gap-2">
                        @if (auth()->user()->role !== 'admin_logistic')
                            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm flex-shrink-0">
                                + New Project
                            </a>
                        @endif
                        <a href="{{ route('projects.export', request()->query()) }}"
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
                    <form id="filter-form" method="GET" action="{{ route('projects.index') }}" class="row g-2">
                        <div class="col-md-2">
                            <select id="filter-quantity" name="quantity" class="form-select select2">
                                <option value="">All Quantity</option>
                                @foreach ($projects->pluck('qty')->unique() as $qty)
                                    <option value="{{ $qty }}" {{ request('quantity') == $qty ? 'selected' : '' }}>
                                        {{ $qty }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="filter-department" name="department" class="form-select select2">
                                <option value="">All Department</option>
                                <option value="mascot" {{ request('department') == 'mascot' ? 'selected' : '' }}>Mascot
                                </option>
                                <option value="costume" {{ request('department') == 'costume' ? 'selected' : '' }}>Costume
                                </option>
                                <option value="mascot&costume"
                                    {{ request('department') == 'mascot&costume' ? 'selected' : '' }}>Mascot & Costume
                                </option>
                                <option value="animatronic" {{ request('department') == 'animatronic' ? 'selected' : '' }}>
                                    Animatronic</option>
                                <option value="plustoys" {{ request('department') == 'plustoys' ? 'selected' : '' }}>Plus
                                    Toys</option>
                                <option value="it" {{ request('department') == 'it' ? 'selected' : '' }}>IT</option>
                                <option value="facility" {{ request('department') == 'facility' ? 'selected' : '' }}>
                                    Facility</option>
                                <option value="bag" {{ request('department') == 'bag' ? 'selected' : '' }}>Bag</option>
                            </select>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('projects.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <table class="table table-bordered table-hover" id="datatable">
                    <thead class="align-middle">
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Department</th>
                            <th>Start Date</th>
                            <th>Deadline</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($projects as $project)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $project->name }}</td>
                                <td>{{ $project->qty }}</td>
                                <td>
                                    {{ ucwords(str_replace('&', ' & ', $project->department)) }}
                                </td>
                                <td>{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->translatedFormat('d F Y') : '-' }}
                                </td>
                                <td>
                                    {{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->translatedFormat('d F Y') : '-' }}
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn btn-info btn-sm btn-show-image"
                                            data-bs-toggle="modal" data-bs-target="#imageModal"
                                            data-img="{{ $project->img ? asset('storage/' . $project->img) : '' }}"
                                            data-name="{{ $project->name }}">
                                            Show
                                        </button>
                                        @if (auth()->user()->role !== 'admin_logistic')
                                            <a href="{{ route('projects.edit', $project) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('projects.destroy', $project) }}" method="POST"
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
    <!-- Modal Show Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Project Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="img-container"></div>
                </div>
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

            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true
            });

            // SweetAlert for delete confirmation
            $('.btn-delete').on('click', function(e) {
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

            // Show Image Modal
            $(document).on('click', '.btn-show-image', function() {
                // Reset modal content
                $('#img-container').html('');
                let img = $(this).data('img');
                let name = $(this).data('name');
                $('#imageModalLabel').text(name + ' - Image');
                $('#img-container').html(img ?
                    `<img src="${img}" alt="Image" class="img-fluid mb-2 rounded" style="max-width:100%;">` :
                    '<span class="text-muted">No Image</span>');
            });
        });
    </script>
@endpush
