    @extends('layouts.app')

@section('content')
<div class="container-sm">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="card-title mb-4">Input Timing</h1>
            <form action="{{ route('timings.storeMultiple') }}" method="POST">
                @csrf

                <div class="table-responsive">
                    <table class="table align-middle mb-0" style="min-width:1200px;">
                        <thead class="table-light align-middle">
                            <tr>
                                <th style="width:120px;">Date</th>
                                <th style="width:120px;">Project</th>
                                <th style="width:110px;">Department</th>
                                <th style="width:100px;">Step</th>
                                <th style="width:140px;">Part</th>
                                <th style="width:140px;">Employee</th>
                                <th style="width:90px;">Start</th>
                                <th style="width:90px;">End</th>
                                <th style="width:70px;">Qty</th>
                                <th style="width:110px;">Status</th>
                                <th style="width:140px;">Remarks</th>
                                <th style="width:60px;"></th>
                            </tr>
                        </thead>
                        <tbody id="timing-rows">
                            <tr class="timing-row">
                                <td>
                                    <input type="date" name="timings[0][tanggal]" class="form-control form-control-sm" required>
                                </td>
                                <td>
                                    <select name="timings[0][project_id]" class="form-select form-select-sm select2 project-select" required>
                                        <option value="">Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" data-category="{{ $project->department }}" data-parts='@json($project->parts->pluck("part_name"))'>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="timings[0][category]" class="form-control form-control-sm category-input" placeholder="Department" readonly>
                                </td>
                                <td>
                                    <input type="text" name="timings[0][step]" class="form-control form-control-sm" placeholder="Step" required>
                                </td>
                                <td>
                                    <select name="timings[0][parts]" class="form-select form-select-sm part-select" required>
                                        <option value="">Project Part</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="timings[0][employee_id]" class="form-select form-select-sm select2" required>
                                        <option value="">Employee</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="time" name="timings[0][start_time]" class="form-control form-control-sm" required>
                                </td>
                                <td>
                                    <input type="time" name="timings[0][end_time]" class="form-control form-control-sm" required>
                                </td>
                                <td>
                                    <input type="number" name="timings[0][output_qty]" class="form-control form-control-sm" placeholder="Qty" required>
                                </td>
                                <td>
                                    <select name="timings[0][status]" class="form-select form-select-sm" required>
                                        <option value="complete" style="color:green;">Finished</option>
                                        <option value="on progress" style="color:orange;">On Progress</option>
                                        <option value="not started" style="color:gray;">Not Start</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="timings[0][remarks]" class="form-control form-control-sm" placeholder="Remarks">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-row" style="display:none;">Delete</button>
                                </td>
                            </tr>
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

@push('styles')
<style>
    .timing-row > td {
        vertical-align: middle !important;
        padding-top: 0.4rem !important;
        padding-bottom: 0.4rem !important;
    }
    .timing-row .form-control,
    .timing-row .form-select {
        min-width: 70px;
        font-size: 0.97rem;
    }
    .timing-row .btn-remove-row {
        margin-left: 0.5rem;
    }
    .table > :not(:last-child) > :last-child > * {
        border-bottom-color: #e2e8f0;
    }
</style>
@endpush

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let rowIdx = 1;
function initSelect2(context) {
    let $selects = context ? $(context).find('.select2') : $('.select2');
    $selects.each(function() {
        $(this).select2({
            width: '100%',
            dropdownParent: $(this).closest('tr')
        });
    });
}
$(document).ready(function() {
    initSelect2($('#timing-rows .timing-row').first());

    $('#add-row').click(function() {
        let lastRow = $('.timing-row').last();
        lastRow.find('.select2').each(function() {
            if ($(this).hasClass("select2-hidden-accessible")) {
                $(this).select2('destroy');
            }
        });
        let row = $('.timing-row').first().clone();
        row.find('input, select').each(function() {
            let name = $(this).attr('name');
            if(name) {
                name = name.replace(/\[\d+\]/, '['+rowIdx+']');
                $(this).attr('name', name).val('');
            }
            if($(this).hasClass('category-input')) $(this).val('');
            if($(this).hasClass('part-select')) $(this).html('<option value="">Pilih Part</option>');
        });
        row.find('.btn-remove-row').show();
        $('#timing-rows').append(row);
        initSelect2(row);
        rowIdx++;
    });

    $(document).on('click', '.btn-remove-row', function() {
        $(this).closest('tr').remove();
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
        $partSelect.html('<option value="">Pilih Part</option>');
        if(parts) {
            JSON.parse(parts).forEach(function(part) {
                $partSelect.append(`<option value="${part}">${part}</option>`);
            });
        }
    });
});
</script>
@endpush