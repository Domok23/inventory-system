@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="card-title mb-4">Input Timing</h4>
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{!! $error !!}</li>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </ul>
                    </div>
                @endif
                <form action="{{ route('timings.storeMultiple') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0" style="min-width:1200px;">
                            <thead class="table-light align-middle">
                                <tr>
                                    <th style="width:10%;">Date</th>
                                    <th style="width:12%;">Project</th>
                                    <th style="width:9%;">Department</th>
                                    <th style="width:8%;">Step</th>
                                    <th style="width:12%;">Part</th>
                                    <th style="width:12%;">Employee</th>
                                    <th style="width:7%;">Start</th>
                                    <th style="width:7%;">End</th>
                                    <th style="width:6%;">Qty</th>
                                    <th style="width:9%;">Status</th>
                                    <th style="width:13%;">Remarks</th>
                                    <th style="width:5%;"></th>
                                </tr>
                            </thead>
                            <tbody id="timing-rows">
                                @php
                                    $oldTimings = old('timings') ?? [0 => []];
                                @endphp
                                @foreach ($oldTimings as $i => $timing)
                                    <tr class="timing-row">
                                        <td>
                                            <input type="date" name="timings[{{ $i }}][tanggal]"
                                                class="form-control form-control-sm" required
                                                value="{{ old("timings.$i.tanggal") }}">
                                        </td>
                                        <td>
                                            <select name="timings[{{ $i }}][project_id]"
                                                class="form-select form-select-sm select2 project-select" required>
                                                <option value="">Select Project</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}"
                                                        data-category="{{ $project->department }}"
                                                        data-parts='@json($project->parts->pluck('part_name'))'
                                                        {{ old("timings.$i.project_id") == $project->id ? 'selected' : '' }}>
                                                        {{ $project->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="timings[{{ $i }}][category]"
                                                class="form-control form-control-sm category-input" placeholder="Department"
                                                readonly value="{{ old("timings.$i.category") }}">
                                        </td>
                                        <td>
                                            <input type="text" name="timings[{{ $i }}][step]"
                                                class="form-control form-control-sm" placeholder="Step" required
                                                value="{{ old("timings.$i.step") }}">
                                        </td>
                                        <td>
                                            @php
                                                $selectedProject = $projects->firstWhere(
                                                    'id',
                                                    old("timings.$i.project_id"),
                                                );
                                                $parts = $selectedProject
                                                    ? $selectedProject->parts->pluck('part_name')->toArray()
                                                    : [];
                                                $hasParts = count($parts) > 0;
                                            @endphp
                                            <select name="timings[{{ $i }}][parts]"
                                                class="form-select form-select-sm part-select{{ $errors->has("timings.$i.parts") ? ' is-invalid' : '' }}"
                                                {{ !$hasParts ? 'readonly disabled' : '' }}>
                                                @if (!$hasParts)
                                                    <option value="No Part" selected>No Part</option>
                                                @else
                                                    <option value="">Select Project Part</option>
                                                    @foreach ($parts as $part)
                                                        <option value="{{ $part }}"
                                                            {{ old("timings.$i.parts") == $part ? 'selected' : '' }}>
                                                            {{ $part }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <select name="timings[{{ $i }}][employee_id]"
                                                class="form-select form-select-sm select2" required>
                                                <option value="">Select Employee</option>
                                                @foreach ($employees as $emp)
                                                    <option value="{{ $emp->id }}"
                                                        {{ old("timings.$i.employee_id") == $emp->id ? 'selected' : '' }}>
                                                        {{ $emp->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="time" name="timings[{{ $i }}][start_time]"
                                                class="form-control form-control-sm" required
                                                value="{{ old("timings.$i.start_time") }}">
                                        </td>
                                        <td>
                                            <input type="time" name="timings[{{ $i }}][end_time]"
                                                class="form-control form-control-sm" required
                                                value="{{ old("timings.$i.end_time") }}">
                                        </td>
                                        <td>
                                            <input type="number" name="timings[{{ $i }}][output_qty]"
                                                class="form-control form-control-sm" placeholder="Qty" required
                                                value="{{ old("timings.$i.output_qty") }}">
                                        </td>
                                        <td>
                                            <select name="timings[{{ $i }}][status]"
                                                class="form-select form-select-sm" required>
                                                <option value="pending" style="color:red;"
                                                    {{ old("timings.$i.status") == 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="on progress" style="color:orange;"
                                                    {{ old("timings.$i.status") == 'on progress' ? 'selected' : '' }}>On
                                                    Progress</option>
                                                <option value="complete" style="color:green;"
                                                    {{ old("timings.$i.status") == 'complete' ? 'selected' : '' }}>Finished
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="timings[{{ $i }}][remarks]"
                                                class="form-control form-control-sm" placeholder="Remarks"
                                                value="{{ old("timings.$i.remarks") }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm btn-remove-row"
                                                title="Delete"><i class="bi bi-trash3"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button type="button" class="btn btn-primary" id="add-row">+ Add New Row</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function initSelect2Row($row) {
            $row.find('.select2').select2({
                width: '100%',
                theme: 'bootstrap-5',
                dropdownParent: $row
            });
        }

        $(document).ready(function() {
            initSelect2Row($('.timing-row').first());

            let rowIdx = 1;

            $('#add-row').click(function() {
                let $lastRow = $('.timing-row').last();

                // Destroy select2 pada row yang akan di-clone
                $lastRow.find('.select2').each(function() {
                    if ($(this).data('select2')) {
                        $(this).select2('destroy');
                    }
                });

                // Clone row terakhir
                let $newRow = $lastRow.clone();

                // Ambil value dari row sebelumnya
                let prevDate = $lastRow.find('input[name^="timings"][name$="[tanggal]"]').val();
                let prevProject = $lastRow.find('select[name^="timings"][name$="[project_id]"]').val();
                let prevDept = $lastRow.find('input[name^="timings"][name$="[category]"]').val();
                let prevStart = $lastRow.find('input[name^="timings"][name$="[start_time]"]').val();
                let prevEnd = $lastRow.find('input[name^="timings"][name$="[end_time]"]').val();

                // Reset value dan name index
                $newRow.find('input, select').each(function() {
                    let name = $(this).attr('name');
                    if (name) {
                        name = name.replace(/\[\d+\]/, '[' + rowIdx + ']');
                        $(this).attr('name', name);
                    }
                    // Set value untuk field tertentu, kosongkan yang lain
                    if ($(this).is('[name$="[tanggal]"]')) {
                        $(this).val(prevDate);
                    } else if ($(this).is('[name$="[project_id]"]')) {
                        $(this).val(prevProject).trigger('change');
                    } else if ($(this).is('[name$="[category]"]')) {
                        $(this).val(prevDept);
                    } else if ($(this).is('[name$="[start_time]"]')) {
                        $(this).val(prevStart);
                    } else if ($(this).is('[name$="[end_time]"]')) {
                        $(this).val(prevEnd);
                    } else if ($(this).hasClass('category-input')) {
                        $(this).val('');
                    } else if ($(this).hasClass('part-select')) {
                        $(this).html('<option value="">Select Project Part</option>');
                    } else if (!$(this).is('select')) {
                        $(this).val('');
                    }
                });

                $newRow.find('.btn-remove-row').show();

                // Append row baru
                $('#timing-rows').append($newRow);

                // Inisialisasi select2 pada row baru & row terakhir
                initSelect2Row($newRow);
                initSelect2Row($lastRow);

                rowIdx++;
            });

            $(document).on('click', '.btn-remove-row', function() {
                if ($('.timing-row').length > 1) {
                    $(this).closest('tr').remove();
                }
            });

            // Project change: isi category & parts otomatis
            $(document).on('change', '.project-select', function() {
                let $row = $(this).closest('tr');
                let selected = this.options[this.selectedIndex];
                // Isi category otomatis
                let category = selected.getAttribute('data-category');
                $row.find('.category-input').val(category ? category : '');
                // Isi part otomatis
                let parts = selected.getAttribute('data-parts');
                let $partSelect = $row.find('.part-select');
                $partSelect.html('<option value="">Select Project Part</option>');
                if (parts) {
                    JSON.parse(parts).forEach(function(part) {
                        $partSelect.append(`<option value="${part}">${part}</option>`);
                    });
                }
            });
        });

        $(document).on('change', '.project-select', function() {
            let $row = $(this).closest('tr');
            let selected = this.options[this.selectedIndex];
            let parts = selected.getAttribute('data-parts');
            let $partSelect = $row.find('.part-select');
            if (parts && JSON.parse(parts).length > 0) {
                $partSelect.prop('disabled', false).prop('readonly', false);
                $partSelect.html('<option value="">Select Project Part</option>');
                JSON.parse(parts).forEach(function(part) {
                    $partSelect.append(`<option value="${part}">${part}</option>`);
                });
                $partSelect.val(''); // reset value jika sebelumnya 'No Part'
            } else {
                $partSelect.prop('disabled', true).prop('readonly', true);
                $partSelect.html('<option value="No Part" selected>No Part</option>');
                $partSelect.val('No Part'); // pastikan value-nya benar
            }
        });
    </script>
@endpush
