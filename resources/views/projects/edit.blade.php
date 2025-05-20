@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ isset($project) ? 'Edit Project' : 'Create Project' }}</h2>
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
        <form method="POST" action="{{ isset($project) ? route('projects.update', $project) : route('projects.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if (isset($project))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name">Project Name</label>
                <input type="text" name="name" value="{{ old('name', $project->name ?? '') }}" class="form-control"
                    required>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="qty">Quantity</label>
                <input type="number" name="qty" value="{{ old('qty', $project->qty ?? '') }}" class="form-control"
                    required>
                @error('qty')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="department">Department</label>
                <select name="department" class="form-select" required>
                    <option value="mascot"
                        {{ old('department', $project->department ?? '') == 'mascot' ? 'selected' : '' }}>Mascot</option>
                    <option value="costume"
                        {{ old('department', $project->department ?? '') == 'costume' ? 'selected' : '' }}>Costume</option>
                    <option value="mascot&costume"
                        {{ old('department', $project->department ?? '') == 'mascot&costume' ? 'selected' : '' }}>Mascot &
                        Costume</option>
                </select>
                @error('department')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="deadline">Deadline (optional)</label>
                <input type="date" name="deadline"
                    value="{{ old('deadline', isset($project) ? $project->deadline : '') }}" class="form-control">
                @error('deadline')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="img">Image (optional)</label>
                <input type="file" name="img" class="form-control" id="img" accept="image/*"
                    onchange="previewImage(event)">
                <img id="img-preview" src="{{ isset($project) && $project->img ? asset('storage/' . $project->img) : '' }}"
                    alt="Image Preview" class="mt-2"
                    style="max-width: 200px; display: {{ isset($project) && $project->img ? 'block' : 'none' }};">
                @error('img')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">{{ isset($project) ? 'Update' : 'Create' }}</button>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('img-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }
    </script>
@endpush
