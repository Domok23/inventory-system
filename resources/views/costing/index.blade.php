@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <h3>Costing Report</h3>
                <table class="table table-bordered" id="datatable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Project Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $project->name }}</td>
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
                                <th>Price per Unit</th>
                                <th>Total Cost (IDR)</th>
                            </tr>
                        </thead>
                        <tbody id="costingTableBody">
                            <!-- Data akan dimuat melalui AJAX -->
                        </tbody>
                    </table>
                    <h5 class="text-end" id="grandTotal">Grand Total: IDR 0</h5>
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
                        const row = `
                    <tr>
                        <td>${inventory.name}</td>
                        <td>${material.quantity || 0} ${inventory.unit || ''}</td>
                        <td>${inventory.currency.name || 'N/A'} ${formatCurrency(inventory.price || 0)}</td>
                        <td>${formatCurrency(material.total_cost || 0)}</td>
                    </tr>
                `;
                        tableBody.innerHTML += row;
                    });

                    document.getElementById('grandTotal').innerText =
                        `Grand Total: IDR ${formatCurrency(data.grand_total_idr)}`;
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
