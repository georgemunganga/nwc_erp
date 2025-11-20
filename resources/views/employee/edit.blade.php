@extends('layouts.admin')
@section('page-title')
    {{ __('Edit Employee') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item"><a href="{{ route('employee.index') }}">{{ __('Employee') }}</a></li>
    <li class="breadcrumb-item">{{ $employeesId }}</li>
@endsection


@section('content')
    <div class="row">
        {{ Form::model($employee, ['route' => ['employee.update', $employee->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
        <div class="row">
            <div class="col-md-6 ">
                <div class="card emp_details">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('Personal Detail') }}</h6>
                    </div>
                    <div class="card-body employee-detail-edit-body">

                        <div class="row">
                            <div class="form-group col-md-4">
                                {!! Form::label('first_name', __('First Name'), ['class' => 'form-label']) !!}<x-required></x-required>
                                {!! Form::text('first_name', old('first_name', $employee->first_name), [
                                    'class' => 'form-control',
                                    'required' => 'required',
                                    'id' => 'first_name',
                                    'placeholder' => __('Enter first name'),
                                ]) !!}
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::label('middle_name', __('Middle Name'), ['class' => 'form-label']) !!}
                                {!! Form::text('middle_name', old('middle_name', $employee->middle_name), [
                                    'class' => 'form-control',
                                    'id' => 'middle_name',
                                    'placeholder' => __('Enter middle name'),
                                ]) !!}
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::label('last_name', __('Last Name'), ['class' => 'form-label']) !!}<x-required></x-required>
                                {!! Form::text('last_name', old('last_name', $employee->last_name), [
                                    'class' => 'form-control',
                                    'required' => 'required',
                                    'id' => 'last_name',
                                    'placeholder' => __('Enter last name'),
                                ]) !!}
                            </div>
                            <div class="col-md-6">
                                <x-mobile label="{{ __('Phone') }}" name="phone" required
                                    placeholder="Enter employee phone"></x-mobile>
                            </div>
                            <div class="form-group col-md-6">

                                {!! Form::label('dob', __('Date of Birth'), ['class' => 'form-label']) !!}<x-required></x-required>
                                {!! Form::date('dob', null, ['class' => 'form-control', 'required' => 'required', 'max' => date('Y-m-d')]) !!}

                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<x-required></x-required>
                                <div class="d-flex radio-check ">
                                    <div class="form-check form-check-inline form-group">
                                        <input type="radio" id="g_male" value="Male" name="gender"
                                            class="form-check-input" {{ $employee->gender == 'Male' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="g_male">{{ __('Male') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline form-group">
                                        <input type="radio" id="g_female" value="Female" name="gender"
                                            class="form-check-input" {{ $employee->gender == 'Female' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="g_female">{{ __('Female') }}</label>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="form-group col-md-6">
                                {!! Form::label('ssn', __('SSN'), ['class' => 'form-label']) !!}
                                {!! Form::text('ssn', null, ['class' => 'form-control', 'placeholder' => __('Enter SSN')]) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('man_number', __('Man Number'), ['class' => 'form-label']) !!}
                                {!! Form::text('man_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Man Number')]) !!}
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            {!! Form::label('address', __('Address'), ['class' => 'form-label']) !!}<x-required></x-required>
                            {!! Form::textarea('address', null, [
                                'class' => 'form-control',
                                'rows' => 2,
                                'required' => 'required',
                                'placeholder' => __('Enter employee address'),
                            ]) !!}
                        </div>
                        <small class="text-muted mt-2" id="full-name-helper-edit">
                            {{ __('Full Name') }} : {{ !empty($employee) ? trim($employee->first_name . ' ' . ($employee->middle_name ?: '') . ' ' . $employee->last_name) : '' }}
                        </small>
                        @if (\Auth::user()->type == 'Employee')
                            {!! Form::submit('Update', ['class' => 'btn-create btn-xs badge-blue radius-10px float-right']) !!}
                        @endif
                    </div>
                </div>
            </div>
            @if (\Auth::user()->type != 'Employee')
                <div class="col-md-6 d-flex">
                    <div class="card emp_details">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Company Detail') }}</h6>
                        </div>
                        <div class="card-body employee-detail-edit-body">
                            <div class="row">
                                @csrf
                                <div class="form-group col-md-12">
                                    {!! Form::label('employee_id', __('Employee ID'), ['class' => 'form-label']) !!}
                                    {!! Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('branch_id', __('Branch'), ['class' => 'form-label']) }}
                                    {{ Form::select('branch_id', $branches, $employee->branch_id, ['class' => 'form-control select', 'required' => 'required', 'id' => 'branch_id']) }}
                                    <div class="text-xs mt-1">
                                        {{ __('Create branch here.') }} <a href="{{ route('branch.index') }}"><b>{{ __('Create branch') }}</b></a>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('department_id', __('Department'), ['class' => 'form-label']) }}
                                    {{ Form::select('department_id', $departments, null, ['class' => 'form-control select', 'required' => 'required', 'id' => 'department_id']) }}
                                    <div class="text-xs mt-1">
                                        {{ __('Create department here.') }} <a href="{{ route('department.index') }}"><b>{{ __('Create department') }}</b></a>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('designation_id', __('Designation'), ['class' => 'form-label']) }}
                                    <select class="select form-control " id="designation_id" name="designation_id"
                                        required></select>
                                        <div class="text-xs mt-1">
                                            {{ __('Create designation here.') }} <a href="{{ route('designation.index') }}"><b>{{ __('Create designation') }}</b></a>
                                        </div>
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('company_doj', 'Company Date Of Joining', ['class' => 'form-label']) !!}
                                    {!! Form::date('company_doj', null, ['class' => 'form-control ', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('reports_to', __('Reports To'), ['class' => 'form-label']) !!}
                                    {!! Form::select('reports_to', $managers->pluck('name','id')->prepend(__('Select Manager'), ''), old('reports_to', $employee->reports_to), ['class' => 'form-control select', 'id' => 'reports_to']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-6 d-flex">
                    <div class="employee-detail-wrap ">
                        <div class="card emp_details">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('Company Detail') }}</h6>
                            </div>
                            <div class="card-body employee-detail-edit-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info">
                                            <strong>{{ __('Branch') }}</strong>
                                            <span>{{ !empty($employee->branch) ? $employee->branch->name : '' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info font-style">
                                            <strong>{{ __('Department') }}</strong>
                                            <span>{{ !empty($employee->department) ? $employee->department->name : '' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info font-style">
                                            <strong>{{ __('Designation') }}</strong>
                                            <span>{{ !empty($employee->designation) ? $employee->designation->name : '' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info">
                                            <strong>{{ __('Date Of Joining') }}</strong>
                                            <span>{{ \Auth::user()->dateFormat($employee->company_doj) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @if (\Auth::user()->type != 'Employee')
            <div class="row">
                <div class="col-md-6 d-flex">
                    <div class="card emp_details w-100">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Document') }}</h6>
                        </div>
                        <div class="card-body employee-detail-edit-body">
                            @php
                                $employeedoc = $employee->documents()->pluck('document_value', __('document_id'));
                            @endphp

                            @foreach ($documents as $key => $document)
                                <div class="row">
                                    <div class="form-group col-12">
                                        <div class="float-left col-4">
                                            <label for="document" class="float-left form-label">{{ $document->name }}
                                                @if ($document->is_required == 1)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                        </div>
                                        <div class="float-right col-4">
                                            <input type="hidden" name="emp_doc_id[{{ $document->id }}]" id=""
                                                value="{{ $document->id }}">
                                            <div class="choose-file">
                                                <label for="document[{{ $document->id }}]">
                                                    <input
                                                        class="form-control file-validate @if (!empty($employeedoc[$document->id])) float-left @endif @error('document') is-invalid @enderror"
                                                        @if ($document->is_required == 1 && empty($employeedoc[$document->id])) required @endif
                                                        name="document[{{ $document->id }}]"
                                                        onchange="document.getElementById('{{ 'blah' . $key }}').src = window.URL.createObjectURL(this.files[0])"
                                                        type="file" data-filename="{{ $document->id . '_filename' }}">
                                                    <p id="" class="file-error text-danger"></p>
                                                </label>
                                                <p class="{{ $document->id . '_filename' }}"></p>

                                                @php
                                                    $logo = \App\Models\Utility::get_file('uploads/document/');
                                                @endphp

                                                <div class="choose-file-img">

                                                    <img id="{{ 'blah' . $key }}"
                                                        src="{{ isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id]) ? $logo . '/' . $employeedoc[$document->id] : '' }}" />
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex">
                    <div class="card emp_details">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Bank Account Detail') }}</h6>
                        </div>
                        <div class="card-body employee-detail-edit-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {!! Form::label('account_holder_name', __('Account Holder Name'), ['class' => 'form-label']) !!}
                                    {!! Form::text('account_holder_name', null, [
                                        'class' => 'form-control',
                                        'placeholder' => __('Enter account holder name'),
                                    ]) !!}

                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('account_number', __('Account Number'), ['class' => 'form-label']) !!}
                                    {!! Form::number('account_number', null, [
                                        'class' => 'form-control',
                                        'placeholder' => __('Enter account number'),
                                    ]) !!}

                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']) !!}
                                    {!! Form::text('bank_name', null, ['class' => 'form-control', 'placeholder' => __('Enter bank name')]) !!}

                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' => 'form-label']) !!}
                                    {!! Form::text('bank_identifier_code', null, [
                                        'class' => 'form-control',
                                        'placeholder' => __('Enter bank identifier code'),
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('branch_location', __('Branch Location'), ['class' => 'form-label']) !!}
                                    {!! Form::text('branch_location', null, [
                                        'class' => 'form-control',
                                        'placeholder' => __('Enter branch location'),
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('tax_payer_id', __('Tax Payer Id'), ['class' => 'form-label']) !!}
                                    {!! Form::text('tax_payer_id', null, ['class' => 'form-control', 'placeholder' => __('Enter tax payer id')]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 d-flex">
                    <div class="card emp_details w-100">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Salary Information') }}</h6>
                        </div>
                        <div class="card-body employee-detail-edit-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    {!! Form::label('basic_salary', __('Basic Salary'), ['class' => 'form-label']) !!}
                                    {!! Form::number('basic_salary', null, [
                                        'class' => 'form-control',
                                        'id' => 'basic_salary',
                                        'step' => '0.01',
                                        'placeholder' => __('Enter basic salary'),
                                    ]) !!}
                                    <small class="text-muted">{{ __('Manual input available') }}</small>
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::label('gross_salary', __('Gross Salary'), ['class' => 'form-label']) !!}
                                    {!! Form::number('gross_salary', null, [
                                        'class' => 'form-control',
                                        'id' => 'gross_salary',
                                        'step' => '0.01',
                                        'readonly' => 'readonly',
                                        'placeholder' => __('Auto-calculated'),
                                    ]) !!}
                                    <small class="text-muted">{{ __('Auto-calculated: Basic + Allowances') }}</small>
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::label('net_salary', __('Net Salary'), ['class' => 'form-label']) !!}
                                    {!! Form::number('net_salary', null, [
                                        'class' => 'form-control',
                                        'id' => 'net_salary',
                                        'step' => '0.01',
                                        'readonly' => 'readonly',
                                        'placeholder' => __('Auto-calculated'),
                                    ]) !!}
                                    <small class="text-muted">{{ __('Auto-calculated: Gross - Deductions') }}</small>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3 mb-0">
                                <strong>{{ __('Note:') }}</strong>
                                <ul class="mb-0 mt-2">
                                    <li>{{ __('Basic Salary can be entered manually') }}</li>
                                    <li>{{ __('Gross Salary = Basic Salary + Total Allowances') }}</li>
                                    <li>{{ __('Net Salary = Gross Salary - Deductions + Other Benefits') }}</li>
                                    <li>{{ __('Use the Set Salary page to configure allowances, deductions, and other compensation') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 d-flex">
                    <div class="card emp_details w-100">
                        <div class="card-header">
                            <h6 class="mb-0">{{ __('Identification') }}</h6>
                        </div>
                        <div class="card-body employee-detail-edit-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {!! Form::label('nrc_number', __('National Registration Card Number'), ['class' => 'form-label']) !!}
                                    {!! Form::text('nrc_number', null, ['class' => 'form-control', 'placeholder' => __('Enter NRC number')]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('drivers_license_number', __('Driver\'s License Number'), ['class' => 'form-label']) !!}
                                    {!! Form::text('drivers_license_number', null, ['class' => 'form-control', 'placeholder' => __('Enter driver\'s license number')]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('passport_number', __('Passport Number'), ['class' => 'form-label']) !!}
                                    {!! Form::text('passport_number', null, ['class' => 'form-control', 'placeholder' => __('Enter passport number')]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-6 d-flex">
                    <div class="employee-detail-wrap">
                        <div class="card emp_details">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('Document Detail') }}</h6>
                            </div>
                            <div class="card-body employee-detail-edit-body">
                                <div class="row">
                                    @php
                                        $employeedoc = $employee
                                            ->documents()
                                            ->pluck('document_value', __('document_id'));
                                    @endphp
                                    @foreach ($documents as $key => $document)
                                        <div class="col-md-12">
                                            <div class="info">
                                                <strong>{{ $document->name }}</strong>
                                                <span><a href="{{ !empty($employeedoc[$document->id]) ? asset(Storage::url('uploads/document')) . '/' . $employeedoc[$document->id] : '' }}"
                                                        target="_blank">{{ !empty($employeedoc[$document->id]) ? $employeedoc[$document->id] : '' }}</a></span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex">
                    <div class="employee-detail-wrap">
                        <div class="card emp_details">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('Bank Account Detail') }}</h6>
                            </div>
                            <div class="card-body employee-detail-edit-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info">
                                            <strong>{{ __('Account Holder Name') }}</strong>
                                            <span>{{ $employee->account_holder_name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info font-style">
                                            <strong>{{ __('Account Number') }}</strong>
                                            <span>{{ $employee->account_number }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info font-style">
                                            <strong>{{ __('Bank Name') }}</strong>
                                            <span>{{ $employee->bank_name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info">
                                            <strong>{{ __('Bank Identifier Code') }}</strong>
                                            <span>{{ $employee->bank_identifier_code }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info">
                                            <strong>{{ __('Branch Location') }}</strong>
                                            <span>{{ $employee->branch_location }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info">
                                            <strong>{{ __('Tax Payer Id') }}</strong>
                                            <span>{{ $employee->tax_payer_id }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (\Auth::user()->type != 'Employee')
            <div class="float-end">
                <input type="button" value="{{ __('Cancel') }}"
                    onclick="location.href = '{{ route('employee.index') }}';" class="btn btn-secondary me-2">
                <input type="submit" value="{{ __('Update') }}" class="btn btn-primary ">
            </div>
        @endif

        {!! Form::close() !!}
    </div>
@endsection

@push('script-page')
    <script type="text/javascript">
        $(document).on('change', '#branch_id', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(branch_id) {
            var data = {
                "branch_id": branch_id,
                "_token": "{{ csrf_token() }}",
            }

            $.ajax({
                url: '{{ route('employee.getdepartment') }}',
                method: 'POST',
                data: data,
                success: function(data) {
                    $('#department_id').empty();
                    $('#department_id').append(
                        '<option value="" disabled>{{ __('Select Department') }}</option>');
                    
                    $.each(data, function(key, value) {
                        var selected = '';
                        if (key == '{{ $employee->department_id }}') {
                            selected = 'selected';
                        }

                        $('#department_id').append('<option value="' + key + '"  ' + selected + '>' + value +
                            '</option>');
                    });
                    
                }
            });
        }
    
        function getDesignation(did) {
            $.ajax({
                url: '{{ route('employee.json') }}',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#designation_id').empty();
                    $('#designation_id').append(
                        '<option value="">{{ __('Select Designation') }}</option>');
                    $.each(data, function(key, value) {
                        var select = '';
                        if (key == '{{ $employee->designation_id }}') {
                            select = 'selected';
                        }

                        $('#designation_id').append('<option value="' + key + '"  ' + select + '>' +
                            value + '</option>');
                    });
                }
            });
        }

        $(document).ready(function() {
            var b_id = $('#branch_id').val();
            var d_id = $('#department_id').val();
            var designation_id = '{{ $employee->designation_id }}';
            getDepartment(b_id);
            getDesignation(d_id);
        });

        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getDesignation(department_id);
        });
    </script>
    <script>
        function updateFullNameEdit() {
            const firstName = $('#first_name').val() || '';
            const middleName = $('#middle_name').val() || '';
            const lastName = $('#last_name').val() || '';
            const parts = [firstName, middleName, lastName].filter(part => part.trim() !== '');
            const fullName = parts.join(' ');
            $('#full-name-helper-edit').text('{{ __('Full Name') }} : ' + (fullName || '-'));
        }

        $('#first_name, #middle_name, #last_name').on('input', updateFullNameEdit);
        updateFullNameEdit();

        // Salary auto-calculation
        function calculateSalaries() {
            const basicSalary = parseFloat($('#basic_salary').val()) || 0;

            // For gross salary: Basic + Allowances (simplified - in real scenario, fetch from server)
            // For now, just copy basic to gross as a starting point
            const grossSalary = basicSalary;
            $('#gross_salary').val(grossSalary.toFixed(2));

            // For net salary: Gross - Deductions + Benefits (simplified)
            // For now, just copy gross to net as a starting point
            const netSalary = grossSalary;
            $('#net_salary').val(netSalary.toFixed(2));
        }

        // Calculate on basic salary change
        $('#basic_salary').on('input', function() {
            calculateSalaries();
        });

        // Calculate on page load if basic salary exists
        $(document).ready(function() {
            if ($('#basic_salary').val()) {
                calculateSalaries();
            }
        });
    </script>
@endpush
