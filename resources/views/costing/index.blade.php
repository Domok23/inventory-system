@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.5rem;">Project Costing Report</h2>
                <hr>
                <div class="mb-3">
                    <form id="filter-form" method="GET" action="{{ route('costing.report') }}" class="row g-2">
                        <div class="col-md-3">
                            <select id="filter-department" name="department" class="form-select select2">
                                <option value="">All Departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department }}"
                                        {{ request('department') == $department ? 'selected' : '' }}>
                                        {{ ucfirst($department) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('costing.report') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
                <table class="table table-hover table-bordered table-striped" id="datatable">
                    <thead class="align-middle">
                        <tr>
                            <th></th>
                            <th>Project Name</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($projects as $project)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $project->name }}</td>
                                <td>{{ ucfirst($project->department) ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm"
                                        onclick="viewCosting('{{ $project->id }}')">View</button>
                                    <a href="{{ route('costing.export', $project->id) }}"
                                        class="btn btn-success btn-sm">Export to Excel</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="costingModal" tab index="-1" aria-labelledby="costingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="costingModalLabel">Project Costing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Total Cost (IDR)</th>
                            </tr>
                        </thead>
                        <tbody id="costingTableBody">
                            <!-- Data akan dimuat melalui AJAX -->
                        </tbody>
                    </table>
                    <h5 class="text-end" id="grandTotal">Grand Total: Rp 0</h5>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true,
                stateSave: true,
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

        function formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(value);
        }

        function viewCosting(projectId) {
            fetch(`/costing-report/${projectId}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('costingTableBody');
                    tableBody.innerHTML = '';

                    // Perbarui judul modal dengan nama proyek
                    document.getElementById('costingModalLabel').innerText = `Project Costing: ${data.project}`;

                    data.materials.forEach(material => {
                        const inventory = material.inventory || {
                            name: 'N/A',
                            price: 0,
                            unit: 'N/A',
                            currency: {
                                name: 'N/A'
                            }
                        }; // Default jika inventory null

                        const totalPrice = (inventory.price || 0) * (material.quantity ||
                        0); // Hitung Price x Quantity

                        const row = `
                                        <tr>
                                            <td>${inventory.name}</td>
                                            <td>${material.quantity || 0} ${inventory.unit || ''}</td>
                                            <td>${formatCurrency(inventory.price || 0)} ${inventory.currency.name || 'N/A'}</td>
                                            <td>${formatCurrency(totalPrice)} ${inventory.currency.name || 'N/A'}</td> <!-- Kolom baru -->
                                            <td>${formatCurrency(material.total_cost || 0)} IDR</td>
                                        </tr>
                                    `;
                        tableBody.innerHTML += row;
                    });

                    document.getElementById('grandTotal').innerHTML =
                        `Grand Total: <span class="text-success fw-bold">${formatCurrency(data.grand_total_idr)} IDR</span>`;
                    const modal = new bootstrap.Modal(document.getElementById('costingModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching costing data:', error);
                    alert('Failed to load costing data. Please try again.');
                });
        }
    </script>
@endpush
