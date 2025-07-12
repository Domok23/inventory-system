@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow rounded">
            <div class="card-header">
                <h2 class="mb-0 flex-shrink-0" style="font-size:1.3rem;">Edit Employee</h2>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('employees.update', $employee->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-2">
                        <div class="mb-3">
                            <label for="name" class="form-label">Employee Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $employee->name) }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="position" name="position"
                                value="{{ old('position', $employee->position) }}" required>
                            @error('position')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <select name="department" id="department" class="form-select">
                                <option value="">Select Department</option>
                                <option value="logistic"
                                    {{ old('department', $employee->department ?? '') == 'logistic' ? 'selected' : '' }}>
                                    Logistic
                                </option>
                                <option value="mascot"
                                    {{ old('department', $employee->department ?? '') == 'mascot' ? 'selected' : '' }}>
                                    Mascot
                                </option>
                                <option value="costume"
                                    {{ old('department', $employee->department ?? '') == 'costume' ? 'selected' : '' }}>
                                    Costume
                                </option>
                                <option value="finance"
                                    {{ old('department', $employee->department ?? '') == 'finance' ? 'selected' : '' }}>
                                    Finance
                                </option>
                                <option value="animatronic"
                                    {{ old('department', $employee->department ?? '') == 'animatronic' ? 'selected' : '' }}>
                                    Animatronic
                                </option>
                                <option value="plusstoys"
                                    {{ old('department', $employee->department ?? '') == 'plusstoys' ? 'selected' : '' }}>
                                    Plus Toys
                                </option>
                            </select>
                            @error('department')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Employment Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="active"
                                    {{ old('status', $employee->status ?? 'active') == 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $employee->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                                <option value="terminated"
                                    {{ old('status', $employee->status ?? '') == 'terminated' ? 'selected' : '' }}>
                                    Terminated
                                </option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $employee->email ?? '') }}" placeholder="employee@company.com">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                value="{{ old('phone', $employee->phone ?? '') }}" placeholder="+62 xxx xxxx xxxx">
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hire_date" class="form-label">Hire Date</label>
                            <input type="date" class="form-control" id="hire_date" name="hire_date"
                                value="{{ old('hire_date', $employee->hire_date ?? '') }}">
                            @error('hire_date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="salary" name="salary"
                                    value="{{ old('salary', $employee->salary ?? '') }}" placeholder="0" min="0">
                            </div>
                            @error('salary')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                placeholder="Additional notes about the employee...">{{ old('notes', $employee->notes ?? '') }}</textarea>
                            @error('notes')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Format salary input
            const salaryInput = document.getElementById('salary');
            if (salaryInput) {
                salaryInput.addEventListener('input', function() {
                    // Remove non-numeric characters
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }

            // Format phone number input
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function() {
                    // Basic phone number formatting
                    let value = this.value.replace(/[^0-9+]/g, '');
                    this.value = value;
                });
            }

            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
@endpush
