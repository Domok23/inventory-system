@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.3rem;">
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

                        <div class="mb-3">
                        <label>Parts (opsional)</label>
                        <div id="parts-wrapper">
                        @foreach($project->parts as $part)
                        <div class="input-group mb-2">
                        <input type="text" name="parts[]" class="form-control" value="{{ $part->part_name }}" placeholder="Part name">
                        <button type="button" class="btn btn-danger btn-remove-part">Hapus</button>
                        </div>
                         @endforeach
                        @if($project->parts->isEmpty())
                        <div class="input-group mb-2">
                        <input type="text" name="parts[]" class="form-control" placeholder="Part name">
                            <button type="button" class="btn btn-danger btn-remove-part" style="display:none;">Hapus</button>
                        </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="add-part">Tambah Part</button>
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
                                <option value="mascot"
                                    {{ old('department', $project->department ?? '') == 'mascot' ? 'selected' : '' }}>
                                    Mascot
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
                        <div class="col-lg-4 mb-3">
                            <label for="finish_date" class="form-label">Finish Date (hanya diisi saat project selesai)</label>
                            <input type="date" name="finish_date" id="finish_date"
                                value="{{ old('finish_date', $project->finish_date ?? '') }}"
                                class="form-control"
                                @if(isset($project) && $project->finish_date && auth()->user()->role !== 'super_admin') readonly @endif>
                            @error('finish_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label for="img" class="form-label">Image (optional)</label>
                            <input type="file" name="img" class="form-control" id="img" accept="image/*"
                                onchange="previewImage(event)">
                            <a id="img-preview-link"
                                href="{{ isset($project) && $project->img ? asset('storage/' . $project->img) : '#' }}"
                                data-fancybox="gallery"
                                @if(isset($project) && $project->img)
                                    style="display: block;"
                                @else
                                    style="display: none;"
                                @endif
                                >
                                <img id="img-preview"
                                    src="{{ isset($project) && $project->img ? asset('storage/' . $project->img) : '#' }}"
                                    alt="Image Preview" class="mt-2 rounded" style="max-width: 200px;">
                            </a>
                            @error('img')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">{{ isset($project) ? 'Update' : 'Create' }}</button>
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
            const maxSize = 2 * 1024 * 1024; // 2 MB

            if (input.files && input.files[0]) {
                // Validasi ukuran file
                if (input.files[0].size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File too large',
                        text: 'Maximum file size is 2 MB.',
                    });
                    input.value = '';
                    if (preview) preview.src = '';
                    if (previewLink) previewLink.href = '#';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview) preview.src = e.target.result;
                    if (previewLink) {
                        previewLink.href = e.target.result;
                        preview.style.display = 'block';
                        previewLink.style.display = 'block';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                if (preview) preview.src = '';
                if (previewLink) previewLink.href = '#';
                if (preview) preview.style.display = 'none';
                if (previewLink) previewLink.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Validasi tanggal di frontend
            const startDateInput = document.getElementById('start_date');
            const deadlineInput = document.getElementById('deadline');
            const form = startDateInput.closest('form');

            function validateDates(e) {
                const startDate = startDateInput.value;
                const deadline = deadlineInput.value;
                // Hanya validasi jika kedua field terisi
                if (startDate && deadline && startDate > deadline) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Date',
                        text: 'Start Date cannot be later than Deadline.',
                    }).then(() => {
                        startDateInput.focus();
                    });
                    return false;
                }
                return true;
            }

            if (form) {
                form.addEventListener('submit', validateDates);
            }

            // Inisialisasi Fancybox untuk gambar
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

document.getElementById('add-part').onclick = function() {
    let wrapper = document.getElementById('parts-wrapper');
    let div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `<input type="text" name="parts[]" class="form-control" placeholder="Part name">
        <button type="button" class="btn btn-danger btn-remove-part">Hapus</button>`;
    wrapper.appendChild(div);
};
document.addEventListener('click', function(e) {
    if(e.target.classList.contains('btn-remove-part')) {
        e.target.parentElement.remove();
    }
});
    </script>
@endpush
