@extends('layouts.admin')

@section('page-title')
    {{ __('Employee') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employee.index') }}">{{ __('Employee') }}</a></li>
    <li class="breadcrumb-item">{{ $employeesId }}</li>
@endsection

@section('action-btn')
    @if (!empty($employee))
        <div class="text-end">
            <div class="d-flex flex-wrap align-items-center justify-content-md-end gap-2 drp-languages">
                <ul class="list-unstyled mb-0">
                    <li class="dropdown dash-h-item status-drp">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="drp-text hide-mob text-primary"> {{ __('Joining Letter') }}</span>
                            <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                        </a>
                        <div class="dropdown-menu icon-dropdown dash-h-dropdown">
                            <a href="{{ route('joiningletter.download.pdf', $employee->id) }}"
                                class=" btn-icon dropdown-item" data-bs-toggle="tooltip" data-bs-placement="top"
                                target="_blanks"><i class="ti ti-download "></i>{{ __('PDF') }}</a>

                            <a href="{{ route('joininglatter.download.doc', $employee->id) }}"
                                class=" btn-icon dropdown-item" data-bs-toggle="tooltip" data-bs-placement="top"
                                target="_blanks"><i class="ti ti-download "></i>{{ __('DOC') }}</a>
                        </div>
                    </li>
                </ul>
                <ul class="list-unstyled mb-0">
                    <li class="dropdown dash-h-item status-drp">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="drp-text hide-mob text-primary"> {{ __('Experience Certificate') }}</span>
                            <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                        </a>
                        <div class="dropdown-menu icon-dropdown dash-h-dropdown">
                            <a href="{{ route('exp.download.pdf', $employee->id) }}" class=" btn-icon dropdown-item"
                                data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                    class="ti ti-download "></i>{{ __('PDF') }}</a>

                            <a href="{{ route('exp.download.doc', $employee->id) }}" class=" btn-icon dropdown-item"
                                data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                    class="ti ti-download "></i>{{ __('DOC') }}</a></a>
                        </div>
                    </li>
                </ul>
                <ul class="list-unstyled mb-0">
                    <li class="dropdown dash-h-item status-drp">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="drp-text hide-mob text-primary"> {{ __('NOC') }}</span>
                            <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                        </a>
                        <div class="dropdown-menu icon-dropdown dash-h-dropdown">
                            <a href="{{ route('noc.download.pdf', $employee->id) }}" class=" btn-icon dropdown-item"
                                data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                    class="ti ti-download "></i>{{ __('PDF') }}</a>

                            <a href="{{ route('noc.download.doc', $employee->id) }}" class=" btn-icon dropdown-item"
                                data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                    class="ti ti-download "></i>{{ __('DOC') }}</a>
                        </div>
                    </li>
                </ul>
                @php
                    $payslipNotice = __('Payslip data is not available yet. Assign salary details or generate payroll to enable printing.');
                @endphp
                <ul class="list-unstyled mb-0">
                    <li class="dash-h-item">
                        @if (!empty($lastPayslip))
                            <a href="{{ route('payslip.payslipPdf', [\Illuminate\Support\Facades\Crypt::encrypt($lastPayslip->id), $lastPayslip->salary_month]) }}"
                                class="btn btn-sm btn-primary me-2" target="_blank" data-bs-toggle="tooltip" title="{{ __('Print Last Payslip') }}">
                                <i class="ti ti-printer"></i> {{ __('Print Last Payslip') }}
                            </a>
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#lastPayslipPreviewModal">
                                <i class="ti ti-eye"></i> {{ __('Payslip Preview') }}
                            </button>
                        @else
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary me-2" onclick="alert({{ json_encode($payslipNotice) }});" data-bs-toggle="tooltip" title="{{ __('Print Last Payslip') }}">
                                <i class="ti ti-printer"></i> {{ __('Print Last Payslip') }}
                            </a>
                            <button type="button" class="btn btn-sm btn-info" onclick="alert({{ json_encode($payslipNotice) }});">
                                <i class="ti ti-eye"></i> {{ __('Payslip Preview') }}
                            </button>
                        @endif
                    </li>
                </ul>
                @can('edit employee')
                    @if($employee->user_id && $employee->user)
                        @if($employee->user->is_enable_login)
                            <form action="{{ route('employee.toggle.login', $employee->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="{{ __('Disable Login') }}">
                                    <i class="ti ti-lock"></i> {{ __('Disable Login') }}
                                </button>
                            </form>
                        @else
                            <form action="{{ route('employee.toggle.login', $employee->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="{{ __('Enable Login') }}">
                                    <i class="ti ti-lock-open"></i> {{ __('Enable Login') }}
                                </button>
                            </form>
                        @endif
                    @else
                        <form action="{{ route('employee.toggle.login', $employee->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Activate Login') }}">
                                <i class="ti ti-user-plus"></i> {{ __('Activate Login') }}
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}"
                        data-bs-toggle="tooltip" title="{{ __('Edit') }}"class="btn btn-sm btn-info">
                        <i class="ti ti-pencil"></i>
                    </a>
                @endcan
            </div>
        </div>
    @endif
@endsection

@section('content')
    @if (!empty($employee))
    <div class="row gy-4">
        <div class="col-md-6 col-12">
            <div class="card h-100 mb-0">
                <div class="card-header">
                <h5>{{ __('Personal Detail') }}</h5>
                </div>
                <div class="card-body employee-detail-body">
                    
                    <div class="row gy-2">
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('EmployeeId') }} : </strong>
                                <span>{{ $employeesId }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info font-style">
                                <strong class="font-bold">{{ __('Name') }} :</strong>
                                <span>{{ !empty($employee) ? $employee->name : '' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info font-style">
                                <strong class="font-bold">{{ __('Email') }} :</strong>
                                <span>{{ !empty($employee) ? $employee->email : '' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Date of Birth') }} :</strong>
                                <span>{{ \Auth::user()->dateFormat(!empty($employee) ? $employee->dob : '') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Phone') }} :</strong>
                                <span>{{ !empty($employee) ? $employee->phone : '' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Address') }} :</strong>
                                <span>{{ !empty($employee) ? $employee->address : '' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Reports To') }} :</strong>
                                <span>{{ !empty($employee->manager) ? $employee->manager->name : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('SSN') }} :</strong>
                                <span>{{ !empty($employee) ? $employee->ssn : '' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Man Number') }} :</strong>
                                <span>{{ !empty($employee) ? $employee->man_number : '' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Salary Type') }} :</strong>
                                <span>{{ !empty($employee->salaryType) ? $employee->salaryType->name : '' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Basic Salary') }} :</strong>
                                <span>{{ !empty($employee->basic_salary) ? \Auth::user()->priceFormat($employee->basic_salary) : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Gross Salary') }} :</strong>
                                <span>{{ !empty($employee->gross_salary) ? \Auth::user()->priceFormat($employee->gross_salary) : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Net Salary') }} :</strong>
                                <span>{{ !empty($employee->net_salary) ? \Auth::user()->priceFormat($employee->net_salary) : '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Login Status') }} :</strong>
                                @if($employee->user_id && $employee->user)
                                    @if($employee->user->is_enable_login)
                                        <span class="badge bg-success">{{ __('Login Enabled') }}</span>
                                    @else
                                        <span class="badge bg-warning">{{ __('Login Disabled') }}</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">{{ __('No Login Access') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card h-100 mb-0">
                <div class="card-header">
                <h5>{{ __('Company Detail') }}</h5>
                </div>
                <div class="card-body employee-detail-body">
                    
                    <div class="row gy-2">
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Branch') }} : </strong>
                                <span>{{ !empty($employee->branch) ? $employee->branch->name : '' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Department') }} :</strong>
                                <span>{{ !empty($employee->department) ? $employee->department->name : '' }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Designation') }} :</strong>
                                <span>{{ !empty($employee->designation) ? $employee->designation->name : '' }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Date Of Joining') }} :</strong>
                                <span>{{ \Auth::user()->dateFormat(!empty($employee) ? $employee->company_doj : '') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card h-100 mb-0">
                <div class="card-header">
                <h5>{{ __('Document Detail') }}</h5>
                </div>
                <div class="card-body employee-detail-body">
                    
                    <div class="row gy-2">
                        @php

                            $employeedoc = !empty($employee)
                                ? $employee->documents()->pluck('document_value', __('document_id'))
                                : [];
                        @endphp
                        @if (!$documents->isEmpty())
                            @foreach ($documents as $key => $document)
                                <div class="col-sm-6 col-12">
                                    <div class="info">
                                        <strong class="font-bold">{{ $document->name }} : </strong>
                                        <span><a href="{{ !empty($employeedoc[$document->id]) ? asset(Storage::url('uploads/document')) . '/' . $employeedoc[$document->id] : '' }}"
                                                target="_blank">{{ !empty($employeedoc[$document->id]) ? $employeedoc[$document->id] : '' }}</a></span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center">
                                No Document Type Added.!
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card h-100 mb-0">
                <div class="card-header">
                <h5>{{ __('Bank Account Detail') }}</h5>
                </div>
                <div class="card-body employee-detail-body">
                    
                    <div class="row gy-2">
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Account Holder Name') }} : </strong>
                                <span>{{ !empty($employee) ? $employee->account_holder_name : '' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Account Number') }} :</strong>
                                <span>{{ !empty($employee) ? $employee->account_number : '' }}</span>
                            </div>
                        </div>

                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Bank Name') }} :</strong>
                                <span>{{ !empty($employee) ? $employee->bank_name : '' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Bank Identifier Code') }} :</strong>
                                <span>{{ !empty($employee) ? $employee->bank_identifier_code : '' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Branch Location') }} :</strong>
                                <span>{{ !empty($employee) ? $employee->branch_location : '' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            <div class="info">
                                <strong class="font-bold">{{ __('Tax Payer Id') }} :</strong>
                                <span>{{ !empty($employee) ? $employee->tax_payer_id : '' }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card h-100 mb-0">
                <div class="card-header">
                <h5>{{ __('Identification Detail') }}</h5>
                </div>
                <div class="card-body employee-detail-body">
                    
                    <div class="row gy-2">
                        @if ($employee->identifications->isNotEmpty())
                            @foreach ($employee->identifications as $identification)
                                <div class="col-sm-6 col-12">
                                    <div class="info">
                                        <strong class="font-bold">{{ $identification->id_type }} :</strong>
                                        <span>{{ $identification->id_number }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="info">
                                    <span>{{ __('No identification records added yet.') }}</span>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!empty($lastPayslip))
        <div class="modal fade" id="lastPayslipPreviewModal" tabindex="-1" aria-labelledby="lastPayslipPreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="lastPayslipPreviewModalLabel">{{ __('Payslip Preview') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0" style="min-height: 600px;">
                        <iframe src="{{ route('payslip.pdf', [$employee->id, $lastPayslip->salary_month]) }}" frameborder="0" class="w-100" style="min-height: 600px;"></iframe>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('payslip.payslipPdf', [\Illuminate\Support\Facades\Crypt::encrypt($lastPayslip->id), $lastPayslip->salary_month]) }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="ti ti-printer"></i> {{ __('Print') }}
                        </a>
                        <a href="{{ route('payslip.pdf', [$employee->id, $lastPayslip->salary_month]) }}" target="_blank" class="btn btn-sm btn-secondary">
                            <i class="ti ti-download"></i> {{ __('Export') }}
                        </a>
                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endif
@endsection
