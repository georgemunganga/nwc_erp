@extends('layouts.admin')
@section('page-title')
    {{ __('Bulk Salary Assignment') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('setsalary.index') }}">{{ __('Employee Salary') }}</a></li>
    <li class="breadcrumb-item">{{ __('Bulk Assignment') }}</li>
@endsection
@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('setsalary.bulk') }}" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label" for="branch">{{ __('Branch') }}</label>
                            <select class="form-control select2" name="branch" id="branch">
                                <option value="">{{ __('All Branches') }}</option>
                                @foreach($branches as $id => $branch)
                                    <option value="{{ $id }}" {{ request('branch') == $id ? 'selected' : '' }}>
                                        {{ $branch }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="department">{{ __('Department') }}</label>
                            <select class="form-control select2" name="department" id="department">
                                <option value="">{{ __('All Departments') }}</option>
                                @foreach($departments as $id => $department)
                                    <option value="{{ $id }}" {{ request('department') == $id ? 'selected' : '' }}>
                                        {{ $department }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="designation">{{ __('Designation') }}</label>
                            <select class="form-control select2" name="designation" id="designation">
                                <option value="">{{ __('All Designations') }}</option>
                                @foreach($designations as $id => $designation)
                                    <option value="{{ $id }}" {{ request('designation') == $id ? 'selected' : '' }}>
                                        {{ $designation }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">{{ __('Filter') }}</button>
                            <a href="{{ route('setsalary.bulk') }}" class="btn btn-outline-secondary w-100">{{ __('Reset') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        @if($employees->isEmpty())
                            <div class="text-center text-muted py-5">{{ __('No employees match the selected filters.') }}</div>
                        @else
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{{ __('Employee ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Branch') }}</th>
                                    <th>{{ __('Department') }}</th>
                                    <th>{{ __('Designation') }}</th>
                                    <th>{{ __('Net Salary') }}</th>
                                    <th>{{ __('Salary (editable)') }}</th>
                                    <th>{{ __('Payslip Type') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($employees as $employee)
                                    <tr>
                                        <td>{{ \Auth::user()->employeeIdFormat($employee->employee_id) }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->branch->name ?? '-' }}</td>
                                        <td>{{ $employee->department->name ?? '-' }}</td>
                                        <td>{{ $employee->designation->name ?? '-' }}</td>
                                        <td>
                                            {{ !empty($employee->get_net_salary()) ? \Auth::user()->priceFormat($employee->get_net_salary()) : '-' }}
                                        </td>
                                        <td>
                                            <input type="number"
                                                   class="form-control form-control-sm bulk-salary"
                                                   data-employee="{{ $employee->id }}"
                                                   value="{{ old('salary_'.$employee->id, $employee->salary) }}">
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm bulk-payslip"
                                                    data-employee="{{ $employee->id }}">
                                                <option value="">{{ __('Select type') }}</option>
                                                @foreach($payslip_type as $key => $value)
                                                    <option value="{{ $key }}" {{ $employee->salary_type == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary bulk-open"
                                                    data-url="{{ route('setsalary.edit', $employee->id) }}"
                                                    data-employee="{{ $employee->id }}">
                                                {{ __('Open Salary Sheet') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.bulk-open').forEach(function (button) {
                button.addEventListener('click', function () {
                    const employeeId = this.dataset.employee;
                    const salaryInput = document.querySelector('.bulk-salary[data-employee="' + employeeId + '"]');
                    const payslipSelect = document.querySelector('.bulk-payslip[data-employee="' + employeeId + '"]');
                    const rawUrl = this.dataset.url;
                    const target = rawUrl.startsWith('http') ? new URL(rawUrl) : new URL(rawUrl, window.location.origin);

                    if (salaryInput && salaryInput.value.trim() !== '') {
                        target.searchParams.set('salary', salaryInput.value.trim());
                    }

                    if (payslipSelect && payslipSelect.value.trim() !== '') {
                        target.searchParams.set('salary_type', payslipSelect.value.trim());
                    }

                    window.location.href = target.toString();
                });
            });
        });
    </script>
@endsection
