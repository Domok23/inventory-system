@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.5rem;">
                    {{ isset($project) ? 'Edit Project' : 'Create Project' }}</h2>
                <hr>
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
                <form method="POST"
                    action="{{ isset($project) ? route('projects.update', $project) : route('projects.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if (isset($project))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $project->name ?? '') }}" class="form-control" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="qty" class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="qty" id="qty"
                                value="{{ old('qty', $project->qty ?? '') }}" class="form-control" required>
                            @error('qty')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
                            <select name="department" id="department" class="form-select" required>
                                <option value="">Select Department</option>
                                <option value="mascot"
                                    {{ old('department', $project->department ?? '') == 'mascot' ? 'selected' : '' }}>Mascot
                                </option>
                                <option value="costume"
                                    {{ old('department', $project->department ?? '') == 'costume' ? 'selected' : '' }}>
                                    Costume
                                </option>
                                <option value="mascot&costume"
                                    {{ old('department', $project->department ?? '') == 'mascot&costume' ? 'selected' : '' }}>
                                    Mascot &
                                    Costume</option>
                                <option value="animatronic"
                                    {{ old('department', $project->department ?? '') == 'animatronic' ? 'selected' : '' }}>
                                    Animatronic</option>
                                <option value="plustoys"
                                    {{ old('department', $project->department ?? '') == 'plustoys' ? 'selected' : '' }}>
                                    Plus Toys</option>
                                <option value="it"
                                    {{ old('department', $project->department ?? '') == 'it' ? 'selected' : '' }}>
                                    IT</option>
                                <option value="facility"
                                    {{ old('department', $project->department ?? '') == 'facility' ? 'selected' : '' }}>
                                    Facility</option>
                                <option value="bag"
                                    {{ old('department', $project->department ?? '') == 'bag' ? 'selected' : '' }}>
                                    Bag</option>
                            </select>
                            @error('department')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label for="start_date" class="form-label">Start Date (optional)</label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date', isset($project) ? $project->start_date : '') }}"
                                class="form-control">
                            @error('start_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label for="deadline" class="form-label">Deadline (optional)</label>
                            <input type="date" name="deadline" id="deadline"
                                value="{{ old('deadline', isset($project) ? $project->deadline : '') }}"
                                class="form-control">
                            @error('deadline')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="img" class="form-label">Image (optional)</label>
                            <input type="file" name="img" class="form-control" id="img" accept="image/*"
                                onchange="previewImage(event)">
                            <a id="img-preview-link" href="#" data-fancybox="gallery" style="display: none;">
                                <img id="img-preview" src="#" alt="Image Preview" class="mt-2 rounded"
                                    style="max-width: 200px;">
                            </a>
                            @error('img')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">{{ isset($project) ? 'Update' : 'Save' }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('img-preview');
            const previewLink = document.getElementById('img-preview-link');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result; // Set src untuk gambar preview
                    previewLink.href = e.target.result; // Set href untuk Fancybox
                    preview.style.display = 'block';
                    previewLink.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                previewLink.href = '#';
                preview.style.display = 'none';
                previewLink.style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            Fancybox.bind("[data-fancybox='gallery']", {
                Toolbar: {
                    display: [
                        "zoom", // Tombol zoom
                        "download", // Tombol download
                        "close" // Tombol close
                    ],
                },
                Thumbs: false, // Nonaktifkan thumbnail jika tidak diperlukan
                Image: {
                    zoom: true, // Aktifkan fitur zoom
                },
                Hash: false,
            });
        });
    </script>
@endpush
