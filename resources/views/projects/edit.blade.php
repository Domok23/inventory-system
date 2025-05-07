@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($project) ? 'Edit Project' : 'Create Project' }}</h2>

    <form method="POST" action="{{ isset($project) ? route('projects.update', $project) : route('projects.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($project)) @method('PUT') @endif

        <div class="mb-3">
            <label for="name">Project Name</label>
            <input type="text" name="name" value="{{ old('name', $project->name ?? '') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="qty">Quantity</label>
            <input type="number" name="qty" value="{{ old('qty', $project->qty ?? '') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="department">Department</label>
            <select name="department" class="form-select" required>
                <option value="mascot" {{ old('department', $project->department ?? '') == 'mascot' ? 'selected' : '' }}>Mascot</option>
                <option value="costume" {{ old('department', $project->department ?? '') == 'costume' ? 'selected' : '' }}>Costume</option>
                <option value="mascot&costume" {{ old('department', $project->department ?? '') == 'mascot&costume' ? 'selected' : '' }}>Mascot & Costume</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="deadline">Deadline (optional)</label>
            <input type="date" name="deadline" value="{{ old('deadline', isset($project) ? $project->deadline : '') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="img">Image (optional)</label>
            <input type="file" name="img" class="form-control">
            @if(isset($project) && $project->img)
                <img src="{{ asset('storage/' . $project->img) }}" width="100" class="mt-2">
            @endif
        </div>

        <button type="submit" class="btn btn-success">{{ isset($project) ? 'Update' : 'Create' }}</button>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
