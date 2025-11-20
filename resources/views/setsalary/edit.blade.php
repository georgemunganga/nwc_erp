@extends('layouts.admin')
@section('page-title')
    {{__('Manage Employee Salary')}} - {{ $employee->name }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('setsalary.index')}}">{{__('Set Salary')}}</a></li>
    <li class="breadcrumb-item">{{ $employee->name }}</li>
@endsection

@push('css-page')
<style>
    /* ==== TOP STRIP ==== */
    .top-strip {
        background: linear-gradient(135deg, #51459d 0%, #002156 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(81, 69, 157, 0.3);
    }
    .employee-identity h4 {
        margin: 0 0 8px 0;
        font-weight: 600;
    }
    .employee-identity .badges span {
        background: rgba(255,255,255,0.25);
        padding: 4px 12px;
        border-radius: 15px;
        margin-right: 8px;
        font-size: 0.85rem;
        display: inline-block;
    }
    .period-selector select, .status-badge select {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.9rem;
    }
    .period-selector select option, .status-badge select option {
        background: #51459d;
        color: white;
    }
    .status-badge .badge {
        padding: 8px 16px;
        font-size: 0.9rem;
    }

    /* ==== TWO COLUMN LAYOUT ==== */
    .two-column-container {
        display: flex;
        gap: 25px;
        align-items: flex-start;
    }
    .salary-builder {
        flex: 1;
        min-width: 0;
    }
    .salary-overview-sticky {
        width: 380px;
        position: sticky;
        top: 20px;
        flex-shrink: 0;
    }

    /* ==== STEP INDICATOR ==== */
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .step {
        flex: 1;
        text-align: center;
        position: relative;
        cursor: pointer;
        padding: 15px 10px;
        transition: all 0.3s ease;
    }
    .step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 30px;
        right: -50%;
        width: 100%;
        height: 2px;
        background: #e0e0e0;
        z-index: 0;
    }
    .step.active:not(:last-child)::after {
        background: #51459d;
    }
    .step .step-number {
        width: 40px;
        height: 40px;
        background: #e0e0e0;
        color: #666;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }
    .step.active .step-number {
        background: #51459d;
        color: white;
        box-shadow: 0 0 0 4px rgba(81, 69, 157, 0.2);
    }
    .step.completed .step-number {
        background: #6fd943;
        color: white;
    }
    .step .step-label {
        font-size: 0.85rem;
        color: #666;
        font-weight: 500;
    }
    .step.active .step-label {
        color: #51459d;
        font-weight: 600;
    }

    /* ==== COLLAPSIBLE SECTIONS ==== */
    .section-card {
        background: white;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .section-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .section-header {
        padding: 20px 25px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e9ecef;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px 12px 0 0;
    }
    .section-header:hover {
        background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
    }
    .section-header.collapsed {
        border-bottom: none;
        border-radius: 12px;
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
    }
    .section-title i {
        font-size: 1.5rem;
        color: #51459d;
    }
    .section-badge {
        background: #51459d;
        color: white;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
    }
    .section-body {
        padding: 25px;
    }
    .section-body.collapse:not(.show) {
        display: none;
    }

    /* ==== FORM STYLES ==== */
    .form-row-custom {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    .form-group-custom label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        display: block;
    }
    .form-group-custom input, .form-group-custom select, .form-group-custom textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    .form-group-custom input:focus, .form-group-custom select:focus, .form-group-custom textarea:focus {
        border-color: #51459d;
        box-shadow: 0 0 0 3px rgba(81, 69, 157, 0.1);
        outline: none;
    }
    .help-text-custom {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 5px;
    }

    /* ==== DATA TABLES ==== */
    .data-list {
        margin-top: 25px;
    }
    .data-item {
        background: #f8f9fa;
        padding: 15px 18px;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
    }
    .data-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    .data-item-info {
        flex: 1;
    }
    .data-item-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 3px;
    }
    .data-item-meta {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .data-item-amount {
        font-weight: 700;
        font-size: 1.1rem;
        margin-right: 15px;
    }
    .data-item-amount.positive {
        color: #6fd943;
    }
    .data-item-amount.negative {
        color: #ff3a6e;
    }
    .data-item-actions {
        display: flex;
        gap: 5px;
    }

    /* ==== STICKY SALARY OVERVIEW ==== */
    .salary-overview-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border: 2px solid #51459d;
        overflow: hidden;
    }
    .overview-header {
        background: linear-gradient(135deg, #51459d 0%, #002156 100%);
        color: white;
        padding: 20px;
        text-align: center;
    }
    .overview-header h5 {
        margin: 0 0 5px 0;
        font-size: 1rem;
        opacity: 0.9;
    }
    .overview-header .net-salary {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 10px 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .overview-header .change-indicator {
        font-size: 0.85rem;
        opacity: 0.9;
    }
    .overview-body {
        padding: 20px;
    }
    .overview-section {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }
    .overview-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .overview-section-title {
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 12px;
        letter-spacing: 0.5px;
    }
    .overview-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
    }
    .overview-label {
        font-size: 0.9rem;
        color: #495057;
    }
    .overview-value {
        font-weight: 700;
        font-size: 1rem;
    }
    .overview-value.positive {
        color: #6fd943;
    }
    .overview-value.negative {
        color: #ff3a6e;
    }
    .overview-value.neutral {
        color: #51459d;
    }
    .total-row {
        background: #f8f9fa;
        padding: 12px 15px;
        border-radius: 8px;
        margin: 15px 0;
    }
    .total-row .overview-label {
        font-weight: 600;
        font-size: 1rem;
    }
    .total-row .overview-value {
        font-size: 1.3rem;
    }

    /* ==== PREVIEW BUTTON ==== */
    .preview-payslip-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #51459d 0%, #002156 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .preview-payslip-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(81, 69, 157, 0.4);
    }

    /* ==== FLASH ANIMATIONS ==== */
    @keyframes flashGreen {
        0%, 100% { background-color: white; }
        50% { background-color: rgba(111, 217, 67, 0.2); }
    }
    @keyframes flashRed {
        0%, 100% { background-color: white; }
        50% { background-color: rgba(255, 58, 110, 0.2); }
    }
    .flash-increase {
        animation: flashGreen 0.6s ease;
    }
    .flash-decrease {
        animation: flashRed 0.6s ease;
    }

    /* ==== EMPTY STATE ==== */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 3rem;
        opacity: 0.3;
        margin-bottom: 10px;
    }

    /* ==== BUTTONS ==== */
    .btn-save {
        background: #6fd943;
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-save:hover {
        background: #5ec436;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(111, 217, 67, 0.3);
    }
    .btn-add {
        background: #51459d;
        color: white;
        padding: 8px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-add:hover {
        background: #002156;
        transform: translateY(-2px);
    }

    /* ==== STEP COUNTER BADGES ==== */
    .step-counter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #51459d 0%, #6c5ce7 100%);
        color: white;
        border-radius: 50%;
        font-weight: 700;
        font-size: 0.9rem;
        margin-right: 10px;
        box-shadow: 0 3px 8px rgba(81, 69, 157, 0.3);
        position: relative;
        transition: all 0.3s ease;
    }
    .step-counter::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px solid #51459d;
        opacity: 0;
        transform: scale(1);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.3;
        }
        50% {
            transform: scale(1.2);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 0;
        }
    }
    .section-header:hover .step-counter {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(81, 69, 157, 0.5);
    }

    /* ==== WIZARD PROGRESS BAR ==== */
    .wizard-progress-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .wizard-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .wizard-step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #e9ecef;
        border: 3px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.3s ease;
        margin-bottom: 10px;
    }
    .wizard-step-number {
        font-size: 1.2rem;
        font-weight: 700;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    .wizard-step-check {
        display: none;
        font-size: 1.5rem;
        color: white;
    }
    .wizard-step-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
        text-align: center;
        transition: all 0.3s ease;
    }
    .wizard-step-connector {
        width: 100px;
        height: 3px;
        background: #e9ecef;
        margin: 0 20px 35px 20px;
        transition: all 0.3s ease;
    }

    /* Active Step */
    .wizard-step.active .wizard-step-circle {
        background: linear-gradient(135deg, #51459d 0%, #6c5ce7 100%);
        border-color: #51459d;
        box-shadow: 0 0 0 4px rgba(81, 69, 157, 0.2);
    }
    .wizard-step.active .wizard-step-number {
        color: white;
    }
    .wizard-step.active .wizard-step-label {
        color: #51459d;
        font-weight: 700;
    }

    /* Completed Step */
    .wizard-step.completed .wizard-step-circle {
        background: #28a745;
        border-color: #28a745;
    }
    .wizard-step.completed .wizard-step-number {
        display: none;
    }
    .wizard-step.completed .wizard-step-check {
        display: block;
    }
    .wizard-step.completed .wizard-step-label {
        color: #28a745;
    }
    .wizard-step.completed + .wizard-step-connector {
        background: #28a745;
    }

    /* ==== WIZARD CONTAINER ==== */
    .wizard-container {
        position: relative;
    }
    .wizard-step-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }
    .wizard-step-content.active {
        display: block;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ==== WIZARD NAVIGATION ==== */
    .wizard-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #e9ecef;
    }
    .wizard-btn-prev,
    .wizard-btn-next,
    .wizard-btn-finish {
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .wizard-btn-prev:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .wizard-btn-next:hover,
    .wizard-btn-finish:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* ==== INLINE EDIT TABLE ==== */
    .inline-edit-table {
        background: white;
        border: 1px solid #e9ecef;
        margin-bottom: 0;
    }
    .inline-edit-table thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    .inline-edit-table thead th {
        font-weight: 600;
        font-size: 0.85rem;
        color: #495057;
        padding: 12px 10px;
        border-bottom: 2px solid #dee2e6;
    }
    .inline-edit-table tbody td {
        padding: 8px 10px;
        vertical-align: middle;
    }
    .inline-edit-table .form-control-sm {
        font-size: 0.875rem;
        padding: 0.375rem 0.5rem;
    }
    .inline-edit-table .inline-amount-input {
        text-align: right;
        font-weight: 600;
        color: #28a745;
    }
    .inline-edit-table .inline-amount-input.text-danger {
        color: #dc3545 !important;
    }
    .inline-edit-form {
        margin: 0;
    }
    .d-contents {
        display: contents;
    }
    .inline-edit-row:hover {
        background-color: #f8f9fa;
    }
    .inline-edit-row td {
        transition: background-color 0.2s ease;
    }
    .inline-edit-table .input-group-text.amount-suffix {
        min-width: 35px;
        background-color: #e9ecef;
        font-weight: 600;
        color: #495057;
    }
    .inline-edit-table .input-group-text.amount-suffix:empty {
        min-width: 0;
        padding: 0;
        border: 0;
    }

    /* ==== CALCULATOR COMPONENT TABLES ==== */
    .calc-component-table {
        background: white;
        border: 1px solid #e9ecef;
        margin-bottom: 0;
    }
    .calc-component-table thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    .calc-component-table thead th {
        font-weight: 600;
        font-size: 0.85rem;
        color: #495057;
        padding: 10px 8px;
        border-bottom: 2px solid #dee2e6;
    }
    .calc-component-table tbody td {
        padding: 6px 8px;
        vertical-align: middle;
    }
    .calc-component-table .form-control-sm,
    .calc-component-table .form-select-sm {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
        height: calc(1.5em + 0.5rem + 2px);
    }
    .calc-component-table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .calc-component-table .calc-amount-allowance {
        text-align: right;
        font-weight: 600;
        color: #28a745;
    }
    .calc-component-table .calc-amount-deduction {
        text-align: right;
        font-weight: 600;
        color: #dc3545;
    }
    .calc-component-table .input-group-text.calc-suffix {
        min-width: 35px;
        background-color: #e9ecef;
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }
    .calc-component-table .input-group-text.calc-suffix:empty {
        display: none;
    }

    /* ==== RESPONSIVE ==== */
    @media (max-width: 992px) {
        .two-column-container {
            flex-direction: column;
        }
        .salary-overview-sticky {
            width: 100%;
            position: relative;
            top: 0;
            order: -1;
        }
        .step-indicator {
            flex-direction: column;
        }
        .step:not(:last-child)::after {
            display: none;
        }
    }
</style>
@endpush

@section('content')
@php
    $defaultSalary = $employee->salary ?? 0;
    $defaultPayslipType = $employee->salary_type;

    $companySettings = \App\Models\Utility::settings();
    $siteCurrency = $companySettings['site_currency'] ?? 'ZMW';
    $payslipComponentsData = \App\Models\PayslipType::where('created_by', \Auth::user()->creatorId())
        ->with(['allowanceOptions', 'deductionOptions', 'taxSlabs'])
        ->get()
        ->keyBy('id');
    $statutoryRates = [
        'napsa' => isset($companySettings['napsa_rate']) ? floatval($companySettings['napsa_rate']) : 5,
        'nhima' => isset($companySettings['nhima_rate']) ? floatval($companySettings['nhima_rate']) : 1,
    ];

    // Calculate totals with percentage handling
    $totalAllowances = $allowances->sum(function($allowance) use ($defaultSalary) {
        return ($allowance->type === 'percentage')
            ? ($allowance->amount * $defaultSalary / 100)
            : $allowance->amount;
    });
    $totalCommissions = $commissions->sum(function($commission) use ($defaultSalary) {
        return ($commission->type === 'percentage')
            ? ($commission->amount * $defaultSalary / 100)
            : $commission->amount;
    });
    $totalLoans = $loans->sum(function($loan) use ($defaultSalary) {
        return ($loan->type === 'percentage')
            ? ($loan->amount * $defaultSalary / 100)
            : $loan->amount;
    });
    $totalDeductions = $saturationdeductions->sum(function($deduction) use ($defaultSalary) {
        return ($deduction->type === 'percentage')
            ? ($deduction->amount * $defaultSalary / 100)
            : $deduction->amount;
    });
    $totalOtherPayments = $otherpayments->sum(function($payment) use ($defaultSalary) {
        return ($payment->type === 'percentage')
            ? ($payment->amount * $defaultSalary / 100)
            : $payment->amount;
    });
    $totalOvertimeAmount = $overtimes->sum(function($overtime) {
        return ($overtime->number_of_days ?? 0) * ($overtime->hours ?? 0) * ($overtime->rate ?? 0);
    });

    $grossSalary = $defaultSalary + $totalAllowances + $totalCommissions + $totalOtherPayments + $totalOvertimeAmount;
    $totalDeductionsAmount = $totalLoans + $totalDeductions;
    $netSalary = $grossSalary - $totalDeductionsAmount;
    $earningsItemsCount = $allowances->count() + $commissions->count() + $otherpayments->count() + $overtimes->count();
@endphp

<!-- TOP STRIP -->
<div class="top-strip">
    <div class="row align-items-center">
        <div class="col-md-5">
            <div class="employee-identity">
                <h4><i class="ti ti-user me-2"></i>{{ $employee->name }}</h4>
                <div class="badges">
                    <span><i class="ti ti-id me-1"></i>{{ \Auth::user()->employeeIdFormat($employee->employee_id) }}</span>
                    @if($employee->branch)
                        <span><i class="ti ti-building me-1"></i>{{ $employee->branch->name }}</span>
                    @endif
                    @if($employee->designation)
                        <span><i class="ti ti-user-check me-1"></i>{{ $employee->designation->name }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="period-selector">
                <label style="font-size: 0.85rem; opacity: 0.9; display: block; margin-bottom: 5px;">
                    <i class="ti ti-calendar me-1"></i>{{__('Payslip Period')}}
                </label>
                <select class="form-select">
                    <option>{{ date('F Y') }}</option>
                    <option>{{ date('F Y', strtotime('-1 month')) }}</option>
                    <option>{{ date('F Y', strtotime('+1 month')) }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-3 text-end">
            <div class="status-badge">
                <label style="font-size: 0.85rem; opacity: 0.9; display: block; margin-bottom: 5px;">{{__('Status')}}</label>
                <span class="badge bg-success" style="font-size: 0.95rem;">
                    <i class="ti ti-check me-1"></i>{{__('Active')}}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- STEP INDICATOR -->

<!-- NET PAY CALCULATOR -->
<div class="section-card" style="background: linear-gradient(135deg, #ffa21d 0%, #ff8c00 100%); border: none; color: white;">
    <div class="section-header" style="border-bottom: 1px solid rgba(255,255,255,0.2); background: transparent;" data-bs-toggle="collapse" data-bs-target="#collapse-calculator">
        <div class="section-title" style="color: white;">
            <i class="ti ti-calculator" style="color: white;"></i>
            <span>{{__('Net Pay Calculator')}}</span>
        </div>
        <div class="gap-2 d-flex align-items-center">
            <span class="bg-white badge text-dark">{{__('Helper Tool')}}</span>
            <i class="ti ti-chevron-down" style="color: white;"></i>
        </div>
    </div>
    <div class="section-body collapse" id="collapse-calculator" style="background: rgba(255,255,255,0.95); color: #495057; border-radius: 0 0 12px 12px;">
        <div class="mb-4 alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            {{__('Enter the desired net pay amount and select a payslip type. The system will calculate and suggest the salary components based on the payslip type configuration.')}}
        </div>

        <div class="form-row-custom">
            <div class="form-group-custom">
                <label>{{__('Desired Net Pay')}}</label>
                <span class="text-danger">*</span>
                <input type="number" id="desired_net_pay" class="form-control" step="0.01" placeholder="0.00">
                <small class="help-text-custom">{{__('Enter the final amount employee should receive')}}</small>
            </div>
            <div class="form-group-custom">
                <label>{{__('Payslip Type for Calculation')}}</label>
                <span class="text-danger">*</span>
                <select id="calc_payslip_type" class="form-control select2">
                    <option value="">{{__('Select Payslip Type')}}</option>
                    @foreach($payslip_type as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                <small class="help-text-custom">{{__('Components will be calculated based on this payslip type')}}</small>
            </div>
        </div>

        <!-- INLINE COMPONENT EDITING TABLES -->
        <div id="calc-components-container" style="display: none;">
            <hr class="my-4">

            <!-- Allowances/Earnings Table -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="ti ti-coin me-2" style="color: #28a745;"></i>{{__('Earnings & Benefits')}}</h6>
                    <button type="button" class="btn btn-sm btn-success calc-add-component-btn" data-category="allowance">
                        <i class="ti ti-plus me-1"></i>{{__('Add Allowance')}}
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm calc-component-table" id="calc-allowances-table">
                        <thead class="table-light">
                            <tr>
                                <th width="35%">{{__('Component')}}</th>
                                <th width="25%">{{__('Type')}} <span class="text-danger">*</span></th>
                                <th width="25%">{{__('Amount')}} <span class="text-danger">*</span></th>
                                <th width="15%" class="text-center">{{__('Actions')}}</th>
                            </tr>
                        </thead>
                        <tbody id="calc-allowances-body">
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Deductions Table -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="ti ti-minus me-2" style="color: #dc3545;"></i>{{__('Deductions')}}</h6>
                    <button type="button" class="btn btn-sm btn-danger calc-add-component-btn" data-category="deduction">
                        <i class="ti ti-plus me-1"></i>{{__('Add Deduction')}}
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm calc-component-table" id="calc-deductions-table">
                        <thead class="table-light">
                            <tr>
                                <th width="35%">{{__('Component')}}</th>
                                <th width="25%">{{__('Type')}} <span class="text-danger">*</span></th>
                                <th width="25%">{{__('Amount')}} <span class="text-danger">*</span></th>
                                <th width="15%" class="text-center">{{__('Actions')}}</th>
                            </tr>
                        </thead>
                        <tbody id="calc-deductions-body">
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <button type="button" id="calculate-btn" class="btn btn-lg" style="background: #ffa21d; color: white; padding: 12px 40px; font-weight: 600;">
                <i class="ti ti-calculator me-2"></i>{{__('Calculate Breakdown')}}
            </button>
        </div>

        <!-- Calculation Results -->
        <div id="calculation-results" class="mt-4" style="display: none;">
            <hr class="my-4">
            <h6 class="mb-3"><i class="ti ti-chart-bar me-2"></i>{{__('Suggested Salary Breakdown')}}</h6>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{__('Component')}}</th>
                                    <th>{{__('Type')}}</th>
                                    <th class="text-end">{{__('Amount')}}</th>
                                </tr>
                            </thead>
                            <tbody id="breakdown-table-body">
                                <!-- Populated by JavaScript -->
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                            <th colspan="2">{{__('Basic Salary (Suggested)')}}</th>
                            <th class="text-end" id="suggested-basic-salary">{{ \Auth::user()->priceFormat(0) }}</th>
                                </tr>
                                <tr class="table-info">
                                    <th colspan="2">{{__('Gross Salary (Basic + Allowances)')}}</th>
                                    <th class="text-end" id="suggested-gross-salary">{{ \Auth::user()->priceFormat(0) }}</th>
                                </tr>
                                <tr class="table-success">
                                    <th colspan="2">{{__('Net Pay (After all components)')}}</th>
                                    <th class="text-end" id="calculated-net-pay">{{ \Auth::user()->priceFormat(0) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="button" id="apply-calculation-btn" class="btn btn-success">
                            <i class="ti ti-check me-1"></i>{{__('Apply to Basic Salary Field')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- WIZARD PROGRESS INDICATOR -->
<div class="wizard-progress-bar">
    <div class="wizard-step" data-step="1">
        <div class="wizard-step-circle">
            <span class="wizard-step-number">1</span>
            <i class="ti ti-check wizard-step-check"></i>
        </div>
        <div class="wizard-step-label">{{__('Base & Account')}}</div>
    </div>
    <div class="wizard-step-connector"></div>
    <div class="wizard-step" data-step="2">
        <div class="wizard-step-circle">
            <span class="wizard-step-number">2</span>
            <i class="ti ti-check wizard-step-check"></i>
        </div>
        <div class="wizard-step-label">{{__('Earnings')}}</div>
    </div>
    <div class="wizard-step-connector"></div>
    <div class="wizard-step" data-step="3">
        <div class="wizard-step-circle">
            <span class="wizard-step-number">3</span>
            <i class="ti ti-check wizard-step-check"></i>
        </div>
        <div class="wizard-step-label">{{__('Deductions')}}</div>
    </div>
</div>

<!-- TWO COLUMN LAYOUT -->
<div class="two-column-container">
    <!-- LEFT COLUMN: SALARY BUILDER -->
    <div class="salary-builder">

        <!-- WIZARD CONTAINER -->
        <div class="wizard-container">

            <!-- STEP 1: BASE SALARY & ACCOUNT -->
            <div class="wizard-step-content active" id="wizard-step-1">
                <div class="section-card">
                    <div class="section-header" style="cursor: default;">
                        <div class="section-title">
                            <span class="step-counter">1</span>
                            <i class="ti ti-currency-dollar"></i>
                            <span>{{__('Base Salary & Account')}}</span>
                        </div>
                        <div class="gap-2 d-flex align-items-center">
                            <span class="section-badge">{{__('Required')}}</span>
                        </div>
                    </div>
                    <div class="section-body">
                {{ Form::model($employee, array('route' => array('employee.salary.update', $employee->id), 'method' => 'POST', 'id' => 'basicSalaryForm')) }}
                <div class="form-row-custom">
                    <div class="form-group-custom">
                        {{ Form::label('salary_type', __('Payslip Type'),['class'=>'']) }}
                        <span class="text-danger">*</span>
                        {{ Form::select('salary_type',$payslip_type,$defaultPayslipType, array('class' => 'form-control select2 salary-input','required'=>'required','id'=>'salary_type')) }}
                        <small class="help-text-custom">{{__('Select the type of payslip for this employee')}}</small>
                    </div>
                    <div class="form-group-custom">
                        {{ Form::label('salary', __('Monthly Basic Salary'),['class'=>'']) }}
                        <span class="text-danger">*</span>
                        {{ Form::number('salary',$defaultSalary, array('class' => 'form-control salary-input','required'=>'required','step'=>'0.01','placeholder'=>'0.00','id'=>'basic_salary')) }}
                        <small class="help-text-custom">{{__('Enter the base monthly salary amount')}}</small>
                    </div>
                </div>
                @can('create set salary')
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn-save">
                            <i class="ti ti-device-floppy me-1"></i>{{__('Save Changes')}}
                        </button>
                    </div>
                @endcan
                {{Form::close()}}

                <!-- Wizard Navigation -->
                <div class="wizard-navigation">
                    <button type="button" class="btn btn-secondary wizard-btn-prev" disabled>
                        <i class="ti ti-arrow-left me-1"></i>{{__('Previous')}}
                    </button>
                    <button type="button" class="btn btn-primary wizard-btn-next">
                        {{__('Next Step')}}<i class="ti ti-arrow-right ms-1"></i>
                    </button>
                </div>

                    </div>
                </div>
            </div>
            <!-- END STEP 1 -->

            <!-- STEP 2: EARNINGS -->
            <div class="wizard-step-content" id="wizard-step-2">
                <div class="section-card">
                    <div class="section-header" style="cursor: default;">
                        <div class="section-title">
                            <span class="step-counter">2</span>
                            <i class="ti ti-trending-up"></i>
                            <span>{{__('Earnings & Benefits')}}</span>
                        </div>
                <div class="gap-2 d-flex align-items-center">
                    <span class="section-badge" data-count="{{ $earningsItemsCount }}">{{ $earningsItemsCount }} {{__('Items')}}</span>
                </div>
                    </div>
                    <div class="section-body">

                <!-- Allowances -->
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ti ti-gift me-2"></i>{{__('Allowances')}}</h6>
                    <button type="button" id="apply-all-components-btn" class="btn btn-sm btn-primary">
                        <i class="ti ti-wand"></i> {{__('Apply All from Payslip Type')}}
                    </button>
                </div>
                <!-- Inline Editable Allowances Table -->
                <div class="table-responsive">
                    <table class="table table-sm inline-edit-table">
                        <thead>
                            <tr>
                                <th width="25%">{{__('Component')}} <span class="text-danger">*</span></th>
                                <th width="20%">{{__('Title')}}</th>
                                <th width="20%">{{__('Type')}} <span class="text-danger">*</span></th>
                                <th width="20%">{{__('Amount')}}</th>
                                <th width="15%" class="text-center">{{__('Actions')}}</th>
                            </tr>
                        </thead>
                        <tbody id="allowance-tbody">
                            @foreach ($allowances as $allowance)
                            <tr class="inline-edit-row" data-id="{{ $allowance->id }}">
                                {{Form::open(array('url'=>'allowance/'.$allowance->id,'method'=>'PUT','class'=>'inline-edit-form d-contents'))}}
                                @csrf
                                <td>
                                    {{ Form::select('allowance_option',$allowance_options,$allowance->allowance_option, array('class' => 'form-control form-control-sm','required'=>'required')) }}
                                </td>
                                <td>
                                    {{ Form::text('title',$allowance->title, array('class' => 'form-control form-control-sm bg-light','readonly'=>'readonly')) }}
                                </td>
                                <td>
                                    @php
                                        $allowanceTypes = ['fixed' => __('Fixed'), 'percentage' => __('Percentage')];
                                        $currentType = !empty($allowance->allowanceOption) ? $allowance->allowanceOption->type : 'fixed';
                                    @endphp
                                    {{ Form::select('type', $allowanceTypes, $currentType, array('class' => 'form-control form-control-sm allowance-type-select','required'=>'required')) }}
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        {{ Form::number('amount',$allowance->amount, array('class' => 'form-control form-control-sm inline-amount-input','required'=>'required','step'=>'0.01')) }}
                                        <span class="input-group-text amount-suffix" data-type="{{ $currentType }}">
                                            {{ $currentType == 'percentage' ? '%' : '' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        @can('edit allowance')
                                        <button type="submit" class="btn btn-success" title="{{__('Save')}}">
                                            <i class="ti ti-device-floppy"></i>
                                        </button>
                                        @endcan
                                        @can('delete allowance')
                                        <button type="button" class="btn btn-danger inline-delete-btn" data-url="{{ route('allowance.destroy', $allowance->id) }}" title="{{__('Delete')}}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                                {{Form::close()}}
                            </tr>
                            @endforeach
                            <!-- Add New Row -->
                            <tr id="allowance-add-row" style="display:none;">
                                {{Form::open(array('url'=>'allowance','method'=>'post','class'=>'inline-edit-form d-contents'))}}
                                @csrf
                                {{ Form::hidden('employee_id',$employee->id) }}
                                <td>
                                    {{ Form::select('allowance_option',$allowance_options,null, array('class' => 'form-control form-control-sm','required'=>'required','id'=>'new_allowance_option')) }}
                                </td>
                                <td>
                                    {{ Form::text('title',null, array('class' => 'form-control form-control-sm','placeholder'=>'Auto-filled','id'=>'new_allowance_title')) }}
                                </td>
                                <td>
                                    @php
                                        $allowanceTypes = ['fixed' => __('Fixed'), 'percentage' => __('Percentage')];
                                    @endphp
                                    {{ Form::select('type', $allowanceTypes, 'fixed', array('class' => 'form-control form-control-sm allowance-type-select','required'=>'required','id'=>'new_allowance_type')) }}
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        {{ Form::number('amount',null, array('class' => 'form-control form-control-sm inline-amount-input','required'=>'required','step'=>'0.01','placeholder'=>'0.00','id'=>'new_allowance_amount')) }}
                                        <span class="input-group-text amount-suffix" id="new-amount-suffix"></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="submit" class="btn btn-success" title="{{__('Add')}}">
                                            <i class="ti ti-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-secondary inline-cancel-btn" title="{{__('Cancel')}}">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>
                                </td>
                                {{Form::close()}}
                            </tr>
                        </tbody>
                    </table>
                </div>

                @can('create allowance')
                <div class="text-center mt-2">
                    <button type="button" class="btn btn-sm btn-outline-primary inline-add-btn" data-target="allowance-add-row">
                        <i class="ti ti-plus me-1"></i>{{__('Add New Allowance')}}
                    </button>
                </div>
                @endcan

                <div class="empty-state" id="allowance-empty-state" style="{{ $allowances->count() ? 'display:none;' : '' }}">
                    <i class="ti ti-gift"></i>
                    <p>{{__('No allowances added yet')}}</p>
                    @can('create allowance')
                    <button type="button" class="btn btn-sm btn-primary mt-2 inline-add-btn" data-target="allowance-add-row">
                        <i class="ti ti-plus me-1"></i>{{__('Add Your First Allowance')}}
                    </button>
                    @endcan
                </div>

                <hr class="my-4">

                <!-- Commissions -->
                <h6 class="mb-3"><i class="ti ti-percentage me-2"></i>{{__('Commissions')}}</h6>
                {{Form::open(array('url'=>'commission','method'=>'post','class'=>'add-form','data-earning-form'=>'commission'))}}
                @csrf
                {{ Form::hidden('employee_id',$employee->id, array()) }}
                <div class="form-row-custom">
                    <div class="form-group-custom">
                        {{ Form::label('title', __('Title')) }}
                        {{ Form::text('title',null, array('class' => 'form-control','required'=>'required','placeholder'=>'Sales Commission')) }}
                    </div>
                    <div class="form-group-custom">
                        {{ Form::label('amount', __('Amount')) }}
                        {{ Form::number('amount',null, array('class' => 'form-control salary-input','required'=>'required','step'=>'0.01','placeholder'=>'0.00')) }}
                    </div>
                </div>
                @can('create commission')
                    <div class="text-end">
                        <button type="submit" class="btn-add">
                            <i class="ti ti-plus me-1"></i>{{__('Add Commission')}}
                        </button>
                    </div>
                @endcan
                {{Form::close()}}

                <div class="data-list" id="commission-list" style="{{ $commissions->count() ? '' : 'display:none;' }}">
                    @foreach ($commissions as $commission)
                    <div class="data-item">
                        <div class="data-item-info">
                            <div class="data-item-title">{{ $commission->title }}</div>
                        </div>
                        <div class="data-item-amount positive">{{  \Auth::user()->priceFormat($commission->amount )}}</div>
                        <div class="data-item-actions">
                            @can('edit commission')
                                <a href="#" data-url="{{ URL::to('commission/'.$commission->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Commission')}}" class="btn btn-sm btn-primary"><i class="ti ti-pencil"></i></a>
                            @endcan
                            @can('delete commission')
                                <a href="#" class="btn btn-sm btn-danger" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('commission-delete-form-{{$commission->id}}').submit();"><i class="ti ti-trash"></i></a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['commission.destroy', $commission->id],'id'=>'commission-delete-form-'.$commission->id, 'style' => 'display:none']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="empty-state" id="commission-empty-state" style="{{ $commissions->count() ? 'display:none;' : '' }}">
                    <i class="ti ti-percentage"></i>
                    <p>{{__('No commissions added yet')}}</p>
                </div>

                <hr class="my-4">

                <!-- Other Payments -->
                <h6 class="mb-3"><i class="ti ti-coin me-2"></i>{{__('Other Payments')}}</h6>
                {{Form::open(array('url'=>'otherpayment','method'=>'post','class'=>'add-form','data-earning-form'=>'otherpayment'))}}
                @csrf
                {{ Form::hidden('employee_id',$employee->id, array()) }}
                <div class="form-row-custom">
                    <div class="form-group-custom">
                        {{ Form::label('title', __('Title')) }}
                        {{ Form::text('title',null, array('class' => 'form-control','required'=>'required','placeholder'=>'Bonus/Reimbursement')) }}
                    </div>
                    <div class="form-group-custom">
                        {{ Form::label('amount', __('Amount')) }}
                        {{ Form::number('amount',null, array('class' => 'form-control salary-input','required'=>'required','step'=>'0.01','placeholder'=>'0.00')) }}
                    </div>
                </div>
                @can('create other payment')
                    <div class="text-end">
                        <button type="submit" class="btn-add">
                            <i class="ti ti-plus me-1"></i>{{__('Add Payment')}}
                        </button>
                    </div>
                @endcan
                {{Form::close()}}

                <div class="data-list" id="otherpayment-list" style="{{ $otherpayments->count() ? '' : 'display:none;' }}">
                    @foreach ($otherpayments as $otherpayment)
                    <div class="data-item">
                        <div class="data-item-info">
                            <div class="data-item-title">{{ $otherpayment->title }}</div>
                        </div>
                        <div class="data-item-amount positive">{{  \Auth::user()->priceFormat($otherpayment->amount )}}</div>
                        <div class="data-item-actions">
                            @can('edit other payment')
                                <a href="#" data-url="{{ URL::to('otherpayment/'.$otherpayment->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Other Payment')}}" class="btn btn-sm btn-primary"><i class="ti ti-pencil"></i></a>
                            @endcan
                            @can('delete other payment')
                                <a href="#" class="btn btn-sm btn-danger" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('payment-delete-form-{{$otherpayment->id}}').submit();"><i class="ti ti-trash"></i></a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['otherpayment.destroy', $otherpayment->id],'id'=>'payment-delete-form-'.$otherpayment->id, 'style' => 'display:none']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="empty-state" id="otherpayment-empty-state" style="{{ $otherpayments->count() ? 'display:none;' : '' }}">
                    <i class="ti ti-coin"></i>
                    <p>{{__('No other payments added yet')}}</p>
                </div>

                <hr class="my-4">

                <!-- Overtime -->
                <h6 class="mb-3"><i class="ti ti-clock me-2"></i>{{__('Overtime')}}</h6>
                {{Form::open(array('url'=>'overtime','method'=>'post','class'=>'add-form','data-earning-form'=>'overtime'))}}
                @csrf
                {{ Form::hidden('employee_id',$employee->id, array()) }}
                <div class="form-row-custom">
                    <div class="form-group-custom">
                        {{ Form::label('title', __('Title')) }}
                        {{ Form::text('title',null, array('class' => 'form-control','required'=>'required','placeholder'=>'Weekend Overtime')) }}
                    </div>
                    <div class="form-group-custom">
                        {{ Form::label('number_of_days', __('Days')) }}
                        {{ Form::number('number_of_days',null, array('class' => 'form-control overtime-input','required'=>'required','step'=>'0.01','placeholder'=>'0')) }}
                    </div>
                    <div class="form-group-custom">
                        {{ Form::label('hours', __('Hours/Day')) }}
                        {{ Form::number('hours',null, array('class' => 'form-control overtime-input','required'=>'required','step'=>'0.01','placeholder'=>'0')) }}
                    </div>
                    <div class="form-group-custom">
                        {{ Form::label('rate', __('Rate/Hour')) }}
                        {{ Form::number('rate',null, array('class' => 'form-control overtime-input','required'=>'required','step'=>'0.01','placeholder'=>'0.00')) }}
                    </div>
                </div>
                @can('create overtime')
                    <div class="text-end">
                        <button type="submit" class="btn-add">
                            <i class="ti ti-plus me-1"></i>{{__('Add Overtime')}}
                        </button>
                    </div>
                @endcan
                {{Form::close()}}

                <div class="data-list" id="overtime-list" style="{{ $overtimes->count() ? '' : 'display:none;' }}">
                    @foreach ($overtimes as $overtime)
                    @php
                        $overtimeTotal = ($overtime->number_of_days ?? 0) * ($overtime->hours ?? 0) * ($overtime->rate ?? 0);
                    @endphp
                    <div class="data-item">
                        <div class="data-item-info">
                            <div class="data-item-title">{{ $overtime->title }}</div>
                            <div class="data-item-meta">{{ $overtime->number_of_days }} days × {{ $overtime->hours }}h × {{ \Auth::user()->priceFormat($overtime->rate) }}</div>
                        </div>
                        <div class="data-item-amount positive">{{ \Auth::user()->priceFormat($overtimeTotal) }}</div>
                        <div class="data-item-actions">
                            @can('edit overtime')
                                <a href="#" data-url="{{ URL::to('overtime/'.$overtime->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit OverTime')}}" class="btn btn-sm btn-primary"><i class="ti ti-pencil"></i></a>
                            @endcan
                            @can('delete overtime')
                                <a href="#" class="btn btn-sm btn-danger" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('overtime-delete-form-{{$overtime->id}}').submit();"><i class="ti ti-trash"></i></a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['overtime.destroy', $overtime->id],'id'=>'overtime-delete-form-'.$overtime->id, 'style' => 'display:none']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="empty-state" id="overtime-empty-state" style="{{ $overtimes->count() ? 'display:none;' : '' }}">
                    <i class="ti ti-clock"></i>
                    <p>{{__('No overtime records added yet')}}</p>
                </div>

                <!-- Wizard Navigation -->
                <div class="wizard-navigation">
                    <button type="button" class="btn btn-secondary wizard-btn-prev">
                        <i class="ti ti-arrow-left me-1"></i>{{__('Previous')}}
                    </button>
                    <button type="button" class="btn btn-primary wizard-btn-next">
                        {{__('Next Step')}}<i class="ti ti-arrow-right ms-1"></i>
                    </button>
                </div>

                    </div>
                </div>
            </div>
            <!-- END STEP 2 -->

            <!-- STEP 3: DEDUCTIONS -->
            <div class="wizard-step-content" id="wizard-step-3">
                <div class="section-card">
                    <div class="section-header" style="cursor: default;">
                        <div class="section-title">
                            <span class="step-counter">3</span>
                            <i class="ti ti-trending-down"></i>
                            <span>{{__('Deductions')}}</span>
                        </div>
                <div class="gap-2 d-flex align-items-center">
                    <span class="section-badge">{{ $loans->count() + $saturationdeductions->count() }} {{__('Items')}}</span>
                </div>
                    </div>
                    <div class="section-body">

                <!-- Loans -->
                <h6 class="mb-3"><i class="ti ti-credit-card me-2"></i>{{__('Loans')}}</h6>
                {{Form::open(array('url'=>'loan','method'=>'post','class'=>'add-form'))}}
                @csrf
                {{ Form::hidden('employee_id',$employee->id, array()) }}
                <div class="form-row-custom">
                    <div class="form-group-custom">
                        {{ Form::label('loan_option', __('Type')) }}
                        {{ Form::select('loan_option',$loan_options,null, array('class' => 'form-control select2','required'=>'required')) }}
                    </div>
                    <div class="form-group-custom">
                        {{ Form::label('title', __('Title')) }}
                        {{ Form::text('title',null, array('class' => 'form-control','required'=>'required','placeholder'=>'Personal Loan')) }}
                    </div>
                    <div class="form-group-custom">
                        {{ Form::label('amount', __('Amount')) }}
                        {{ Form::number('amount',null, array('class' => 'form-control salary-input','required'=>'required','step'=>'0.01','placeholder'=>'0.00')) }}
                    </div>
                </div>
                <div class="form-row-custom">
                    <div class="form-group-custom">
                        {{ Form::label('start_date', __('Start Date')) }}
                        {{ Form::text('start_date',null, array('class' => 'form-control datepicker','required'=>'required')) }}
                    </div>
                    <div class="form-group-custom">
                        {{ Form::label('end_date', __('End Date')) }}
                        {{ Form::text('end_date',null, array('class' => 'form-control datepicker','required'=>'required')) }}
                    </div>
                    <div class="form-group-custom">
                        {{ Form::label('reason', __('Reason')) }}
                        {{ Form::text('reason',null, array('class' => 'form-control','required'=>'required','placeholder'=>'Loan reason')) }}
                    </div>
                </div>
                @can('create loan')
                    <div class="text-end">
                        <button type="submit" class="btn-add">
                            <i class="ti ti-plus me-1"></i>{{__('Add Loan')}}
                        </button>
                    </div>
                @endcan
                {{Form::close()}}

                @if($loans->count() > 0)
                <div class="data-list">
                    @foreach ($loans as $loan)
                    <div class="data-item">
                        <div class="data-item-info">
                            <div class="data-item-title">{{ $loan->title }}</div>
                            <div class="data-item-meta">{{ !empty($loan->loanOption) ? $loan->loanOption->name : '' }} • {{ \Auth::user()->dateFormat($loan->start_date) }} - {{ \Auth::user()->dateFormat($loan->end_date) }}</div>
                        </div>
                        <div class="data-item-amount negative">{{  \Auth::user()->priceFormat($loan->amount) }}</div>
                        <div class="data-item-actions">
                            @can('edit loan')
                                <a href="#" data-url="{{ URL::to('loan/'.$loan->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Loan')}}" class="btn btn-sm btn-primary"><i class="ti ti-pencil"></i></a>
                            @endcan
                            @can('delete loan')
                                <a href="#" class="btn btn-sm btn-danger" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('loan-delete-form-{{$loan->id}}').submit();"><i class="ti ti-trash"></i></a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['loan.destroy', $loan->id],'id'=>'loan-delete-form-'.$loan->id, 'style' => 'display:none']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="ti ti-credit-card"></i>
                    <p>{{__('No loans added yet')}}</p>
                </div>
                @endif

                <hr class="my-4">

                <!-- Deductions -->
                <h6 class="mb-3"><i class="ti ti-minus-vertical me-2"></i>{{__('Other Deductions')}}</h6>
                {{Form::open(array('url'=>'saturationdeduction','method'=>'post','class'=>'add-form'))}}
                @csrf
                {{ Form::hidden('employee_id',$employee->id, array()) }}
                <div class="form-row-custom">
                    <div class="form-group-custom">
                        {{ Form::label('deduction_option', __('Type')) }}
                        {{ Form::select('deduction_option',$deduction_options,null, array('class' => 'form-control select2','required'=>'required')) }}
                    </div>
                    <div class="form-group-custom">
                        {{ Form::label('title', __('Title')) }}
                        {{ Form::text('title',null, array('class' => 'form-control','required'=>'required','placeholder'=>'Health Insurance')) }}
                    </div>
                    <div class="form-group-custom">
                        {{ Form::label('amount', __('Amount')) }}
                        {{ Form::number('amount',null, array('class' => 'form-control salary-input','required'=>'required','step'=>'0.01','placeholder'=>'0.00')) }}
                    </div>
                </div>
                @can('create saturation deduction')
                    <div class="text-end">
                        <button type="submit" class="btn-add">
                            <i class="ti ti-plus me-1"></i>{{__('Add Deduction')}}
                        </button>
                    </div>
                @endcan
                {{Form::close()}}

                <!-- Inline Editable Deductions Table -->
                <div class="table-responsive">
                    <table class="table table-sm inline-edit-table">
                        <thead>
                            <tr>
                                <th width="25%">{{__('Component')}} <span class="text-danger">*</span></th>
                                <th width="20%">{{__('Title')}}</th>
                                <th width="20%">{{__('Type')}} <span class="text-danger">*</span></th>
                                <th width="20%">{{__('Amount')}}</th>
                                <th width="15%" class="text-center">{{__('Actions')}}</th>
                            </tr>
                        </thead>
                        <tbody id="deduction-tbody">
                            @foreach ($saturationdeductions as $saturationdeduction)
                            <tr class="inline-edit-row" data-id="{{ $saturationdeduction->id }}">
                                {{Form::open(array('url'=>'saturationdeduction/'.$saturationdeduction->id,'method'=>'PUT','class'=>'inline-edit-form d-contents'))}}
                                @csrf
                                <td>
                                    {{ Form::select('deduction_option',$deduction_options,$saturationdeduction->deduction_option, array('class' => 'form-control form-control-sm','required'=>'required')) }}
                                </td>
                                <td>
                                    {{ Form::text('title',$saturationdeduction->title, array('class' => 'form-control form-control-sm bg-light','readonly'=>'readonly')) }}
                                </td>
                                <td>
                                    @php
                                        $deductionTypes = ['fixed' => __('Fixed'), 'percentage' => __('Percentage')];
                                        $currentDeductionType = !empty($saturationdeduction->deductionOption) ? $saturationdeduction->deductionOption->type : 'fixed';
                                    @endphp
                                    {{ Form::select('type', $deductionTypes, $currentDeductionType, array('class' => 'form-control form-control-sm deduction-type-select','required'=>'required')) }}
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        {{ Form::number('amount',$saturationdeduction->amount, array('class' => 'form-control form-control-sm inline-amount-input text-danger','required'=>'required','step'=>'0.01')) }}
                                        <span class="input-group-text amount-suffix" data-type="{{ $currentDeductionType }}">
                                            {{ $currentDeductionType == 'percentage' ? '%' : '' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        @can('edit saturation deduction')
                                        <button type="submit" class="btn btn-success" title="{{__('Save')}}">
                                            <i class="ti ti-device-floppy"></i>
                                        </button>
                                        @endcan
                                        @can('delete saturation deduction')
                                        <button type="button" class="btn btn-danger inline-delete-btn" data-url="{{ route('saturationdeduction.destroy', $saturationdeduction->id) }}" title="{{__('Delete')}}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                                {{Form::close()}}
                            </tr>
                            @endforeach
                            <!-- Add New Row -->
                            <tr id="deduction-add-row" style="display:none;">
                                {{Form::open(array('url'=>'saturationdeduction','method'=>'post','class'=>'inline-edit-form d-contents'))}}
                                @csrf
                                {{ Form::hidden('employee_id',$employee->id) }}
                                <td>
                                    {{ Form::select('deduction_option',$deduction_options,null, array('class' => 'form-control form-control-sm','required'=>'required','id'=>'new_deduction_option')) }}
                                </td>
                                <td>
                                    {{ Form::text('title',null, array('class' => 'form-control form-control-sm','placeholder'=>'Auto-filled','id'=>'new_deduction_title')) }}
                                </td>
                                <td>
                                    @php
                                        $deductionTypes = ['fixed' => __('Fixed'), 'percentage' => __('Percentage')];
                                    @endphp
                                    {{ Form::select('type', $deductionTypes, 'fixed', array('class' => 'form-control form-control-sm deduction-type-select','required'=>'required','id'=>'new_deduction_type')) }}
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        {{ Form::number('amount',null, array('class' => 'form-control form-control-sm inline-amount-input text-danger','required'=>'required','step'=>'0.01','placeholder'=>'0.00','id'=>'new_deduction_amount')) }}
                                        <span class="input-group-text amount-suffix" id="new-deduction-suffix"></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button type="submit" class="btn btn-success" title="{{__('Add')}}">
                                            <i class="ti ti-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-secondary inline-cancel-btn" title="{{__('Cancel')}}">
                                            <i class="ti ti-x"></i>
                                        </button>
                                    </div>
                                </td>
                                {{Form::close()}}
                            </tr>
                        </tbody>
                    </table>
                </div>

                @can('create saturation deduction')
                <div class="text-center mt-2">
                    <button type="button" class="btn btn-sm btn-outline-primary inline-add-btn" data-target="deduction-add-row">
                        <i class="ti ti-plus me-1"></i>{{__('Add New Deduction')}}
                    </button>
                </div>
                @endcan

                <div class="empty-state" id="deduction-empty-state" style="{{ $saturationdeductions->count() ? 'display:none;' : '' }}">
                    <i class="ti ti-minus-vertical"></i>
                    <p>{{__('No deductions added yet')}}</p>
                    @can('create saturation deduction')
                    <button type="button" class="btn btn-sm btn-primary mt-2 inline-add-btn" data-target="deduction-add-row">
                        <i class="ti ti-plus me-1"></i>{{__('Add Your First Deduction')}}
                    </button>
                    @endcan
                </div>

                <!-- Wizard Navigation -->
                <div class="wizard-navigation">
                    <button type="button" class="btn btn-secondary wizard-btn-prev">
                        <i class="ti ti-arrow-left me-1"></i>{{__('Previous')}}
                    </button>
                    <button type="button" class="btn btn-success wizard-btn-finish">
                        <i class="ti ti-check me-1"></i>{{__('Finish')}}
                    </button>
                </div>

                    </div>
                </div>
            </div>
            <!-- END STEP 3 -->

        </div>
        <!-- END WIZARD CONTAINER -->

    </div>

    <!-- RIGHT COLUMN: STICKY SALARY OVERVIEW -->
    <div class="salary-overview-sticky">
        <div class="salary-overview-card">
            <div class="overview-header">
                <h5>{{__('Net Salary')}}</h5>
                <div class="net-salary" id="display-net-salary">{{ \Auth::user()->priceFormat($netSalary) }}</div>
                <div class="change-indicator">
                    <span id="change-text">{{__('Current calculation')}}</span>
                </div>
            </div>
            <div class="overview-body">

                <!-- Base Salary -->
                <div class="overview-section">
                    <div class="overview-section-title">{{__('Base')}}</div>
                    <div class="overview-row">
                        <span class="overview-label">{{__('Basic Salary')}}</span>
                        <span class="overview-value neutral" id="display-basic-salary">{{ \Auth::user()->priceFormat($defaultSalary) }}</span>
                    </div>
                </div>

                <!-- Earnings -->
                <div class="overview-section">
                    <div class="overview-section-title">{{__('Earnings (+)')}}</div>
                    <div class="overview-row">
                        <span class="overview-label">{{__('Allowances')}}</span>
                        <span class="overview-value positive" id="display-allowances">{{ \Auth::user()->priceFormat($totalAllowances) }}</span>
                    </div>
                    <div class="overview-row">
                        <span class="overview-label">{{__('Commissions')}}</span>
                        <span class="overview-value positive" id="display-commissions">{{ \Auth::user()->priceFormat($totalCommissions) }}</span>
                    </div>
                    <div class="overview-row">
                        <span class="overview-label">{{__('Other Payments')}}</span>
                        <span class="overview-value positive" id="display-other-payments">{{ \Auth::user()->priceFormat($totalOtherPayments) }}</span>
                    </div>
                    <div class="overview-row">
                        <span class="overview-label">{{__('Overtime')}}</span>
                        <span class="overview-value positive" id="display-overtime">{{ \Auth::user()->priceFormat($totalOvertimeAmount) }}</span>
                    </div>
                    <div class="total-row">
                        <span class="overview-label">{{__('Total Earnings')}}</span>
                        <span class="overview-value positive" id="display-total-earnings">{{ \Auth::user()->priceFormat($totalAllowances + $totalCommissions + $totalOtherPayments + $totalOvertimeAmount) }}</span>
                    </div>
                </div>

                <!-- Deductions -->
                <div class="overview-section">
                    <div class="overview-section-title">{{__('Deductions (-)')}}</div>
                    <div class="overview-row">
                        <span class="overview-label">{{__('Loans')}}</span>
                        <span class="overview-value negative" id="display-loans">{{ \Auth::user()->priceFormat($totalLoans) }}</span>
                    </div>
                    <div class="overview-row">
                        <span class="overview-label">{{__('Other Deductions')}}</span>
                        <span class="overview-value negative" id="display-deductions">{{ \Auth::user()->priceFormat($totalDeductions) }}</span>
                    </div>
                    <div class="total-row">
                        <span class="overview-label">{{__('Total Deductions')}}</span>
                        <span class="overview-value negative" id="display-total-deductions">{{ \Auth::user()->priceFormat($totalDeductionsAmount) }}</span>
                    </div>
                </div>

                <!-- Gross Salary -->
                <div class="overview-section">
                    <div class="total-row" style="background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);">
                        <span class="overview-label">{{__('Gross Salary')}}</span>
                        <span class="overview-value neutral" id="display-gross-salary">{{ \Auth::user()->priceFormat($grossSalary) }}</span>
                    </div>
                </div>

                <!-- Preview Button -->
                <button class="mt-3 preview-payslip-btn" type="button">
                    <i class="ti ti-file-invoice"></i>
                    <span>{{__('Preview Payslip')}}</span>
                </button>

            </div>
        </div>
    </div>

</div>

<!-- Hidden values for JavaScript calculations -->
<input type="hidden" id="hidden-basic-salary" value="{{ $defaultSalary }}">
<input type="hidden" id="hidden-allowances" value="{{ $totalAllowances }}">
<input type="hidden" id="hidden-commissions" value="{{ $totalCommissions }}">
<input type="hidden" id="hidden-other-payments" value="{{ $totalOtherPayments }}">
<input type="hidden" id="hidden-overtime" value="{{ $totalOvertimeAmount }}">
<input type="hidden" id="hidden-loans" value="{{ $totalLoans }}">
<input type="hidden" id="hidden-deductions" value="{{ $totalDeductions }}">

@endsection

@push('script-page')
<script>
$(document).ready(function () {
    // ==== WIZARD NAVIGATION ====
    let currentStep = 1;
    const totalSteps = 3;

    function updateWizard() {
        // Hide all step contents
        $('.wizard-step-content').removeClass('active');

        // Show current step content
        $(`#wizard-step-${currentStep}`).addClass('active');

        // Update progress bar
        $('.wizard-step').removeClass('active completed');

        for (let i = 1; i <= totalSteps; i++) {
            const $step = $(`.wizard-step[data-step="${i}"]`);
            if (i < currentStep) {
                $step.addClass('completed');
            } else if (i === currentStep) {
                $step.addClass('active');
            }
        }

        // Update navigation buttons
        $('.wizard-btn-prev').prop('disabled', currentStep === 1);

        if (currentStep === totalSteps) {
            $('.wizard-btn-next').hide();
            $('.wizard-btn-finish').show();
        } else {
            $('.wizard-btn-next').show();
            $('.wizard-btn-finish').hide();
        }

        // Scroll to top smoothly
        $('html, body').animate({
            scrollTop: $('.wizard-progress-bar').offset().top - 100
        }, 400);
    }

    // Next button
    $('.wizard-btn-next').on('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
            updateWizard();
        }
    });

    // Previous button
    $('.wizard-btn-prev').on('click', function() {
        if (currentStep > 1) {
            currentStep--;
            updateWizard();
        }
    });

    // Finish button - Save basic salary form before completing
    $('.wizard-btn-finish').on('click', function() {
        const $finishBtn = $(this);
        const originalText = $finishBtn.html();

        // Disable button and show loading
        $finishBtn.prop('disabled', true).html('<i class="ti ti-loader ti-spin me-1"></i>{{ __("Saving...") }}');

        // Submit the basic salary form via AJAX
        const formData = $('#basicSalaryForm').serialize();
        const formAction = $('#basicSalaryForm').attr('action');

        $.ajax({
            url: formAction,
            type: 'POST',
            data: formData,
            success: function(response) {
                show_toastr('{{ __("Success") }}', '{{ __("Salary configuration completed! All changes have been saved.") }}', 'success');

                // Re-enable button
                $finishBtn.prop('disabled', false).html(originalText);

                // Optionally redirect after short delay
                setTimeout(function() {
                    // You can redirect to employee list or stay on page
                    // window.location.href = '{{ route("setsalary.index") }}';
                }, 1500);
            },
            error: function(xhr) {
                let errorMsg = '{{ __("Failed to save salary configuration.") }}';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }

                show_toastr('{{ __("Error") }}', errorMsg, 'error');

                // Re-enable button
                $finishBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Allow clicking on progress bar steps (only completed or current)
    $('.wizard-step').on('click', function() {
        const stepNum = parseInt($(this).data('step'));
        if (stepNum <= currentStep || $(this).hasClass('completed')) {
            currentStep = stepNum;
            updateWizard();
        }
    });

    // Initialize wizard
    updateWizard();

    // ==== INLINE EDITING FUNCTIONALITY ====

    // Show add row when clicking "Add New" button
    $('.inline-add-btn').on('click', function() {
        const target = $(this).data('target');
        $('#' + target).show();
        $('#' + target.replace('-row', '-empty-state')).hide();
        // Focus first input
        $('#' + target + ' input:first, #' + target + ' select:first').focus();
    });

    // Hide add row when clicking cancel
    $('.inline-cancel-btn').on('click', function() {
        $(this).closest('tr').hide();
        // Clear form
        $(this).closest('form')[0].reset();
    });

    // Handle inline delete with confirmation
    $(document).on('click', '.inline-delete-btn', function(e) {
        e.preventDefault();
        const deleteUrl = $(this).data('url');
        const $row = $(this).closest('tr');

        if (confirm('{{ __("Are You Sure?") }}\n{{ __("This action can not be undone. Do you want to continue?") }}')) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    show_toastr('{{ __("Success") }}', response.success || '{{ __("Item deleted successfully") }}', 'success');
                    $row.fadeOut(300, function() {
                        $(this).remove();
                        // Check if tbody is empty
                        const tbody = $row.closest('tbody');
                        if (tbody.find('tr:visible').length === 0) {
                            tbody.closest('.table-responsive').next('.empty-state').show();
                        }
                    });
                },
                error: function(xhr) {
                    show_toastr('{{ __("Error") }}', xhr.responseJSON?.message || '{{ __("An error occurred") }}', 'error');
                }
            });
        }
    });

    // Handle inline form submission via AJAX
    $(document).on('submit', '.inline-edit-form', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        const originalHtml = $submitBtn.html();

        $submitBtn.prop('disabled', true).html('<i class="ti ti-loader ti-spin"></i>');

        $.ajax({
            url: $form.attr('action'),
            type: $form.find('input[name="_method"]').val() || 'POST',
            data: $form.serialize(),
            success: function(response) {
                show_toastr('{{ __("Success") }}', response.success || '{{ __("Saved successfully") }}', 'success');
                $submitBtn.prop('disabled', false).html(originalHtml);

                // If this was an add form, hide the add row and optionally reload or add to table
                if ($form.closest('tr').attr('id') && $form.closest('tr').attr('id').includes('add-row')) {
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                show_toastr('{{ __("Error") }}', xhr.responseJSON?.message || '{{ __("An error occurred") }}', 'error');
                $submitBtn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // Auto-fill title from allowance option selection (for new row)
    $('#new_allowance_option').on('change', function() {
        const selectedText = $(this).find('option:selected').text();
        $('#new_allowance_title').val(selectedText);
    });

    // Handle type selection change - update amount suffix (% or empty)
    $(document).on('change', '.allowance-type-select', function() {
        const selectedType = $(this).val();
        const $row = $(this).closest('tr');
        const $suffix = $row.find('.amount-suffix');

        if (selectedType === 'percentage') {
            $suffix.text('%');
            $suffix.attr('data-type', 'percentage');
        } else {
            $suffix.text('');
            $suffix.attr('data-type', 'fixed');
        }
    });

    // Handle new allowance type change
    $('#new_allowance_type').on('change', function() {
        const selectedType = $(this).val();
        const $suffix = $('#new-amount-suffix');

        if (selectedType === 'percentage') {
            $suffix.text('%');
        } else {
            $suffix.text('');
        }
    });

    // Auto-fill title from deduction option selection (for new row)
    $('#new_deduction_option').on('change', function() {
        const selectedText = $(this).find('option:selected').text();
        $('#new_deduction_title').val(selectedText);
    });

    // Handle deduction type selection change - update amount suffix (% or empty)
    $(document).on('change', '.deduction-type-select', function() {
        const selectedType = $(this).val();
        const $row = $(this).closest('tr');
        const $suffix = $row.find('.amount-suffix');

        if (selectedType === 'percentage') {
            $suffix.text('%');
            $suffix.attr('data-type', 'percentage');
        } else {
            $suffix.text('');
            $suffix.attr('data-type', 'fixed');
        }
    });

    // Handle new deduction type change
    $('#new_deduction_type').on('change', function() {
        const selectedType = $(this).val();
        const $suffix = $('#new-deduction-suffix');

        if (selectedType === 'percentage') {
            $suffix.text('%');
        } else {
            $suffix.text('');
        }
    });

    // ==== EXISTING CODE ====
    const salaryCurrency = @json($siteCurrency);
    const itemsLabel = @json(__('Items'));
    const earningBadge = $('#section-earnings .section-badge');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const earningTargets = {
        allowance: {
            list: '#allowance-list',
            empty: '#allowance-empty-state',
            hidden: '#hidden-allowances',
        },
        commission: {
            list: '#commission-list',
            empty: '#commission-empty-state',
            hidden: '#hidden-commissions',
        },
        otherpayment: {
            list: '#otherpayment-list',
            empty: '#otherpayment-empty-state',
            hidden: '#hidden-other-payments',
        },
        overtime: {
            list: '#overtime-list',
            empty: '#overtime-empty-state',
            hidden: '#hidden-overtime',
        },
    };
    function escapeHtml(value) {
        return $('<div>').text(value).html();
    }
    function bumpEarningsCount() {
        let count = parseInt(earningBadge.data('count')) || 0;
        count += 1;
        earningBadge.data('count', count);
        earningBadge.text(count + ' ' + itemsLabel);
    }
    function addToHidden(category, amount) {
        const target = earningTargets[category];
        if (!target || typeof amount === 'undefined' || amount === null) {
            return;
        }
        const input = $(target.hidden);
        if (!input.length) {
            return;
        }
        const current = parseFloat(input.val()) || 0;
        input.val((current + parseFloat(amount)).toFixed(2));
    }
    function appendEarningItem(category, payload) {
        const target = earningTargets[category];
        if (!target || !payload) {
            return;
        }
        const list = $(target.list);
        const empty = $(target.empty);
        const metaHtml = payload.meta ? '<div class="data-item-meta">' + escapeHtml(payload.meta) + '</div>' : '';
        const rowHtml = '<div class="data-item">' +
            '<div class="data-item-info">' +
            '<div class="data-item-title">' + escapeHtml(payload.title) + '</div>' +
            metaHtml +
            '</div>' +
            '<div class="data-item-amount positive">' + formatCurrency(payload.amount) + '</div>' +
            '</div>';
        list.append(rowHtml);
        list.show();
        empty.hide();
        bumpEarningsCount();
    }
    function handleAjaxError(response) {
        const payload = response.responseJSON || response;
        const message = (payload && (payload.message || payload.error)) || '{{__("Unable to add the entry right now.")}}';
        if (typeof show_toastr === 'function') {
            show_toastr('Error', message, 'error');
        } else {
            alert(message);
        }
    }
    $('.add-form[data-earning-form]').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const category = form.data('earning-form');
        const target = earningTargets[category];
        if (!target) {
            return;
        }
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method') || 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success' && response.data) {
                    appendEarningItem(category, response.data);
                    addToHidden(category, response.data.amount);
                    updateSalaryCalculations();
                    form.trigger('reset');
                    if (typeof show_toastr === 'function') {
                        show_toastr('Success', response.message, 'success');
                    }
                } else {
                    handleAjaxError(response);
                }
            },
            error: function (xhr) {
                handleAjaxError(xhr);
            },
        });
    });
    // Currency formatting function
    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: salaryCurrency,
            minimumFractionDigits: 2
        }).format(amount);
    }

    // Flash animation on value change
    function flashElement(element, type) {
        element.removeClass('flash-increase flash-decrease');
        if (type === 'increase') {
            element.addClass('flash-increase');
        } else if (type === 'decrease') {
            element.addClass('flash-decrease');
        }
        setTimeout(function() {
            element.removeClass('flash-increase flash-decrease');
        }, 600);
    }

    // Real-time calculation and update
    function updateSalaryCalculations() {
        let basicSalary = parseFloat($('#basic_salary').val()) || 0;
        let allowances = parseFloat($('#hidden-allowances').val()) || 0;
        let commissions = parseFloat($('#hidden-commissions').val()) || 0;
        let otherPayments = parseFloat($('#hidden-other-payments').val()) || 0;
        let overtime = parseFloat($('#hidden-overtime').val()) || 0;
        let loans = parseFloat($('#hidden-loans').val()) || 0;
        let deductions = parseFloat($('#hidden-deductions').val()) || 0;

        let totalEarnings = allowances + commissions + otherPayments + overtime;
        let totalDeductions = loans + deductions;
        let grossSalary = basicSalary + totalEarnings;
        let netSalary = grossSalary - totalDeductions;

        // Update display with animation
        $('#display-basic-salary').text(formatCurrency(basicSalary));
        $('#display-allowances').text(formatCurrency(allowances));
        $('#display-commissions').text(formatCurrency(commissions));
        $('#display-other-payments').text(formatCurrency(otherPayments));
        $('#display-overtime').text(formatCurrency(overtime));
        $('#display-total-earnings').text(formatCurrency(totalEarnings));
        $('#display-loans').text(formatCurrency(loans));
        $('#display-deductions').text(formatCurrency(deductions));
        $('#display-total-deductions').text(formatCurrency(totalDeductions));
        $('#display-gross-salary').text(formatCurrency(grossSalary));

        let previousNet = parseFloat($('#display-net-salary').text().replace(/[^0-9.-]+/g, "")) || 0;
        $('#display-net-salary').text(formatCurrency(netSalary));

        if (netSalary > previousNet) {
            flashElement($('.net-salary'), 'increase');
        } else if (netSalary < previousNet) {
            flashElement($('.net-salary'), 'decrease');
        }
    }

    // Listen for changes on salary inputs
    $('.salary-input, .overtime-input').on('input', function() {
        updateSalaryCalculations();
    });

    // Auto-save basic salary and payslip type with debounce
    let saveBasicSalaryTimeout;
    $('#basic_salary, #salary_type').on('change input', function() {
        const $field = $(this);
        const fieldName = $field.attr('id') === 'basic_salary' ? 'Basic Salary' : 'Payslip Type';

        // Clear previous timeout
        clearTimeout(saveBasicSalaryTimeout);

        // Set new timeout for auto-save (1 second after last change)
        saveBasicSalaryTimeout = setTimeout(function() {
            const formData = $('#basicSalaryForm').serialize();
            const formAction = $('#basicSalaryForm').attr('action');

            // Show saving indicator
            const $saveIndicator = $('<small class="text-muted ms-2 auto-save-indicator"><i class="ti ti-loader ti-spin me-1"></i>{{ __("Saving...") }}</small>');
            $field.closest('.form-group-custom').find('.auto-save-indicator').remove();
            $field.closest('.form-group-custom').append($saveIndicator);

            $.ajax({
                url: formAction,
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Update indicator to show saved
                    $saveIndicator.html('<i class="ti ti-check me-1" style="color: #28a745;"></i>{{ __("Saved") }}');

                    // Remove indicator after 2 seconds
                    setTimeout(function() {
                        $saveIndicator.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 2000);
                },
                error: function(xhr) {
                    // Show error indicator
                    $saveIndicator.html('<i class="ti ti-alert-circle me-1" style="color: #dc3545;"></i>{{ __("Failed to save") }}');

                    // Remove indicator after 3 seconds
                    setTimeout(function() {
                        $saveIndicator.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 3000);
                }
            });
        }, 1000); // 1 second debounce
    });

    // Step indicator click
    $('.step').on('click', function() {
        let step = $(this).data('step');
        $('.step').removeClass('active');
        $(this).addClass('active');

        // Collapse all sections
        $('.section-body').removeClass('show');

        // Open relevant section
        if (step === 'base') {
            $('#collapse-base').addClass('show');
        } else if (step === 'earnings') {
            $('#collapse-earnings').addClass('show');
        } else if (step === 'deductions') {
            $('#collapse-deductions').addClass('show');
        }
    });

    // Mark steps as completed based on content
    if ($('#basic_salary').val() > 0) {
        $('.step[data-step="base"]').addClass('completed');
    }
    if ($('.data-item').length > 0) {
        $('.step[data-step="earnings"], .step[data-step="deductions"]').addClass('completed');
    }

    // Section header collapse toggle icon
    $('.section-header').on('click', function() {
        let icon = $(this).find('.ti-chevron-down, .ti-chevron-up');
        icon.toggleClass('ti-chevron-down ti-chevron-up');
    });

    // ===== NET PAY CALCULATOR =====
    // Payslip type components data (will be fetched via AJAX)
    let payslipComponents = @json($payslipComponentsData);
    let statutoryRates = @json($statutoryRates);

    // Counter for unique row IDs
    let calcComponentCounter = 0;

    // Populate component tables when payslip type changes
    $('#calc_payslip_type').on('change', function() {
        let payslipTypeId = $(this).val();

        if (!payslipTypeId) {
            $('#calc-components-container').slideUp();
            return;
        }

        let payslipType = payslipComponents[payslipTypeId];
        if (!payslipType) {
            $('#calc-components-container').slideUp();
            return;
        }

        // Populate allowances table
        populateCalcComponentTable('allowance', payslipType.allowance_options || payslipType.allowanceOptions || []);

        // Populate deductions table
        populateCalcComponentTable('deduction', payslipType.deduction_options || payslipType.deductionOptions || []);

        // Show the component tables
        $('#calc-components-container').slideDown();
    });

    function populateCalcComponentTable(category, components) {
        let tbody = category === 'allowance' ? $('#calc-allowances-body') : $('#calc-deductions-body');
        tbody.empty();

        if (!components || components.length === 0) {
            tbody.append('<tr><td colspan="4" class="text-center text-muted"><em>' + '{{__("No components available")}}' + '</em></td></tr>');
            return;
        }

        components.forEach(function(component) {
            addCalcComponentRow(category, component.name, component.type || 'fixed', parseFloat(component.amount) || 0);
        });
    }

    function addCalcComponentRow(category, name = '', type = 'fixed', amount = 0) {
        calcComponentCounter++;
        let rowId = 'calc-row-' + calcComponentCounter;
        let tbody = category === 'allowance' ? $('#calc-allowances-body') : $('#calc-deductions-body');
        let amountClass = category === 'allowance' ? 'calc-amount-allowance' : 'calc-amount-deduction';
        let suffixText = type === 'percentage' ? '%' : '';

        let row = `
            <tr id="${rowId}" data-category="${category}">
                <td>
                    <input type="text" class="form-control form-control-sm calc-component-name"
                           value="${escapeHtml(name)}" placeholder="{{__('Component Name')}}" />
                </td>
                <td>
                    <select class="form-select form-select-sm calc-component-type">
                        <option value="fixed" ${type === 'fixed' ? 'selected' : ''}>{{__('Fixed')}}</option>
                        <option value="percentage" ${type === 'percentage' ? 'selected' : ''}>{{__('Percentage')}}</option>
                    </select>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" step="0.01" class="form-control form-control-sm calc-component-amount ${amountClass}"
                               value="${amount}" placeholder="0.00" />
                        <span class="input-group-text calc-suffix">${suffixText}</span>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger calc-remove-row-btn" data-row-id="${rowId}">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        tbody.append(row);
    }

    // Handle type change - show/hide % suffix
    $(document).on('change', '.calc-component-type', function() {
        let selectedType = $(this).val();
        let $suffix = $(this).closest('tr').find('.calc-suffix');

        if (selectedType === 'percentage') {
            $suffix.text('%').show();
        } else {
            $suffix.text('').hide();
        }
    });

    // Handle add component button
    $('.calc-add-component-btn').on('click', function() {
        let category = $(this).data('category');
        addCalcComponentRow(category);
    });

    // Handle remove row button
    $(document).on('click', '.calc-remove-row-btn', function() {
        let rowId = $(this).data('row-id');
        $('#' + rowId).fadeOut(300, function() {
            $(this).remove();
        });
    });

    $('#calculate-btn').on('click', function() {
        let desiredNetPay = parseFloat($('#desired_net_pay').val());
        let payslipTypeId = $('#calc_payslip_type').val();

        if (!desiredNetPay || desiredNetPay <= 0) {
            alert('{{__("Please enter a valid net pay amount")}}');
            return;
        }

        if (!payslipTypeId) {
            alert('{{__("Please select a payslip type")}}');
            return;
        }

        // Get payslip type components
        let payslipType = payslipComponents[payslipTypeId];
        if (!payslipType) {
            alert('{{__("Invalid payslip type selected")}}');
            return;
        }

        // Get components from inline tables
        let allowanceData = getCalcTableData('allowance');
        let deductionData = getCalcTableData('deduction');

        // Calculate backwards from net pay
        let breakdown = calculateSalaryBreakdown(desiredNetPay, allowanceData, deductionData, payslipType);

        // Display results and store component data
        displayBreakdown(breakdown, allowanceData, deductionData);
        $('#calculation-results').slideDown();
    });

    // Read component data from inline tables
    function getCalcTableData(category) {
        let tbody = category === 'allowance' ? $('#calc-allowances-body') : $('#calc-deductions-body');
        let components = [];

        tbody.find('tr[data-category]').each(function() {
            let $row = $(this);
            let name = $row.find('.calc-component-name').val();
            let type = $row.find('.calc-component-type').val();
            let amount = parseFloat($row.find('.calc-component-amount').val()) || 0;

            if (name && amount >= 0) {
                components.push({
                    name: name,
                    type: type,
                    amount: amount
                });
            }
        });

        return components;
    }

    function calculateSalaryBreakdown(netPay, allowanceData, deductionData, payslipType) {
        // Use inline table data instead of payslipType defaults
        let allowanceTemplates = prepareComponentTemplates(allowanceData, 'earning');
        let deductionTemplates = prepareComponentTemplates(deductionData, 'deduction');

        let result = solveForBasic(netPay, allowanceTemplates, deductionTemplates, payslipType);

        return {
            basicSalary: result.basic,
            grossSalary: result.gross,
            netPay: result.net,
            components: result.components,
            totalAllowances: result.totalAllowances,
            totalDeductions: result.totalDeductions
        };
    }

    function solveForBasic(netPay, allowanceTemplates, deductionTemplates, payslipType) {
        let lower = 0;
        let upper = Math.max(netPay * 4, 1000) + 500;
        let best = buildResult(upper, allowanceTemplates, deductionTemplates, payslipType);

        for (let i = 0; i < 60; i++) {
            let mid = (lower + upper) / 2;
            let result = buildResult(mid, allowanceTemplates, deductionTemplates, payslipType);
            if (Math.abs(result.net - netPay) < 0.005) {
                best = result;
                break;
            }
            if (result.net < netPay) {
                lower = mid;
            } else {
                upper = mid;
            }
            best = result;
        }

        return best;
    }

    function buildResult(basicSalary, allowanceTemplates, deductionTemplates, payslipType) {
        let allowanceComponents = allowanceTemplates.map(template => computeComponent(template, basicSalary));
        let deductionComponents = deductionTemplates.map(template => computeComponent(template, basicSalary));

        let totalAllowances = allowanceComponents.reduce((sum, component) => sum + component.amount, 0);
        let totalDeductionTemplates = deductionComponents.reduce((sum, component) => sum + component.amount, 0);

        let gross = basicSalary + totalAllowances;
        let statutoryComponents = calculateStatutoryComponents(gross, payslipType);
        let statutoryTotal = statutoryComponents.reduce((sum, component) => sum + component.amount, 0);

        let totalDeductions = totalDeductionTemplates + statutoryTotal;
        let net = gross - totalDeductions;

        return {
            basic: Math.round(basicSalary * 100) / 100,
            gross: Math.round(gross * 100) / 100,
            net: Math.round(net * 100) / 100,
            components: [...allowanceComponents, ...deductionComponents, ...statutoryComponents],
            totalAllowances: Math.round(totalAllowances * 100) / 100,
            totalDeductions: Math.round(totalDeductions * 100) / 100
        };
    }

    function prepareComponentTemplates(items, category) {
        return (items || []).map(item => {
            let type = (item.type || 'fixed').toString().toLowerCase();
            let amount = parseFloat(item.amount) || 0;
            return {
                name: item.name || 'Component',
                category: category,
                type: type,
                isPercentage: type === 'percentage',
                percentage: type === 'percentage' ? amount : 0,
                fixedAmount: type === 'percentage' ? 0 : amount
            };
        });
    }

    function computeComponent(template, basicSalary) {
        let amount = template.isPercentage ? (basicSalary * template.percentage / 100) : template.fixedAmount;
        amount = Math.round(amount * 100) / 100;
        let labelBase = template.isPercentage ? template.percentage + '%' : 'Fixed';
        let label = template.category === 'earning'
            ? 'Allowance (' + labelBase + ')'
            : 'Deduction (' + labelBase + ')';

        return {
            name: template.name,
            type: label,
            amount: amount,
            category: template.category,
            isPercentage: template.isPercentage
        };
    }

    function calculateStatutoryComponents(gross, payslipType) {
        let components = [];
        let deductionItems = payslipType.deduction_options || payslipType.deductionOptions || [];

        let hasDeduction = keyword => deductionItems.some(item => item.name && item.name.toLowerCase().includes(keyword));

        let taxSlabs = payslipType.tax_slabs || payslipType.taxSlabs || [];
        if (taxSlabs.length && !hasDeduction('paye')) {
            let payeAmount = calculatePayee(gross, taxSlabs);
            if (payeAmount > 0) {
                components.push({
                    name: 'PAYE Tax',
                    type: 'Statutory',
                    amount: payeAmount,
                    category: 'deduction'
                });
            }
        }

        if (!hasDeduction('napsa') && statutoryRates.napsa > 0) {
            let amount = Math.round((gross * statutoryRates.napsa / 100) * 100) / 100;
            if (amount > 0) {
                components.push({
                    name: 'NAPSA',
                    type: 'Statutory',
                    amount: amount,
                    category: 'deduction'
                });
            }
        }

        if (!hasDeduction('nhima') && statutoryRates.nhima > 0) {
            let amount = Math.round((gross * statutoryRates.nhima / 100) * 100) / 100;
            if (amount > 0) {
                components.push({
                    name: 'NHIMA',
                    type: 'Statutory',
                    amount: amount,
                    category: 'deduction'
                });
            }
        }

        return components;
    }

    function calculatePayee(gross, taxSlabs) {
        if (!taxSlabs.length) {
            return 0;
        }

        let slabs = taxSlabs.slice().sort((a, b) => (parseFloat(a.min_salary) || 0) - (parseFloat(b.min_salary) || 0));
        let paye = 0;

        slabs.forEach(slab => {
            let min = parseFloat(slab.min_salary) || 0;
            let max = slab.max_salary !== null && slab.max_salary !== undefined ? parseFloat(slab.max_salary) : null;
            let rate = parseFloat(slab.rate) || 0;

            if (gross <= min) {
                return;
            }

            let upper = max !== null ? Math.min(gross, max) : gross;
            let taxable = upper - min;
            if (taxable <= 0) {
                return;
            }

            paye += taxable * (rate / 100);
        });

        return Math.round(paye * 100) / 100;
    }

    function displayBreakdown(breakdown, allowanceData, deductionData) {
        let tbody = $('#breakdown-table-body');
        tbody.empty();

        breakdown.components.forEach(function(component) {
            let badge = component.category === 'earning'
                ? '<span class="badge bg-success">Earning (+)</span>'
                : '<span class="badge bg-danger">Deduction (-)</span>';

            let row = '<tr>' +
                '<td>' + component.name + '</td>' +
                '<td>' + badge + ' ' + component.type + '</td>' +
                '<td class="text-end ' + (component.category === 'earning' ? 'text-success' : 'text-danger') + '">' +
                (component.category === 'earning' ? '+' : '-') + formatCurrency(component.amount) +
                '</td>' +
                '</tr>';
            tbody.append(row);
        });

        let commissions = parseFloat($('#hidden-commissions').val()) || 0;
        let otherPayments = parseFloat($('#hidden-other-payments').val()) || 0;
        let overtime = parseFloat($('#hidden-overtime').val()) || 0;
        let loans = parseFloat($('#hidden-loans').val()) || 0;

        let previewEarnings = breakdown.totalAllowances + commissions + otherPayments + overtime;
        let previewGross = breakdown.basicSalary + previewEarnings;
        let previewDeductions = breakdown.totalDeductions;
        let previewTotalDeductions = previewDeductions + loans;
        let previewNet = previewGross - previewTotalDeductions;

        $('#suggested-basic-salary').text(formatCurrency(breakdown.basicSalary));
        $('#suggested-gross-salary').text(formatCurrency(breakdown.grossSalary));
        $('#calculated-net-pay').text(formatCurrency(previewNet));

        $('#display-basic-salary').text(formatCurrency(breakdown.basicSalary));
        $('#display-allowances').text(formatCurrency(breakdown.totalAllowances));
        $('#display-total-earnings').text(formatCurrency(previewEarnings));
        $('#display-deductions').text(formatCurrency(previewDeductions));
        $('#display-total-deductions').text(formatCurrency(previewTotalDeductions));
        $('#display-gross-salary').text(formatCurrency(previewGross));
        $('#display-net-salary').text(formatCurrency(previewNet));

        // Store all data for the apply button
        $('#calculation-results').data('suggested-basic', breakdown.basicSalary);
        $('#calculation-results').data('allowance-data', allowanceData || []);
        $('#calculation-results').data('deduction-data', deductionData || []);

        $('#hidden-allowances').val(breakdown.totalAllowances.toFixed(2));
        $('#hidden-deductions').val(breakdown.totalDeductions.toFixed(2));
    }

    // Apply calculated basic salary AND components to the form
    $('#apply-calculation-btn').on('click', function() {
        let suggestedBasic = $('#calculation-results').data('suggested-basic');
        let allowanceData = $('#calculation-results').data('allowance-data') || [];
        let deductionData = $('#calculation-results').data('deduction-data') || [];

        if (!suggestedBasic) {
            alert('{{__("No calculation data available")}}');
            return;
        }

        var employeeId = {{ $employee->id }};
        var applyBtn = $(this);
        var originalText = applyBtn.html();

        // Disable button and show loading
        applyBtn.prop('disabled', true);
        applyBtn.html('<i class="ti ti-loader ti-spin"></i> {{__("Applying...")}}');

        // Step 1: Apply basic salary
        $('#basic_salary').val(suggestedBasic).trigger('input');

        // Step 2: Apply allowances via AJAX
        var allowancePromises = [];
        allowanceData.forEach(function(allowance) {
            if (allowance.name && allowance.amount > 0) {
                var promise = $.ajax({
                    url: '{{ route("allowance.store") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        employee_id: employeeId,
                        allowance_option: '', // Will need to match by name or create
                        title: allowance.name,
                        type: allowance.type,
                        amount: allowance.amount
                    }
                });
                allowancePromises.push(promise);
            }
        });

        // Step 3: Apply deductions via AJAX
        var deductionPromises = [];
        deductionData.forEach(function(deduction) {
            if (deduction.name && deduction.amount > 0) {
                var promise = $.ajax({
                    url: '{{ route("saturationdeduction.store") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        employee_id: employeeId,
                        deduction_option: '', // Will need to match by name or create
                        title: deduction.name,
                        type: deduction.type,
                        amount: deduction.amount
                    }
                });
                deductionPromises.push(promise);
            }
        });

        // Wait for all AJAX calls to complete
        Promise.all([...allowancePromises, ...deductionPromises])
            .then(function(responses) {
                // Re-enable button
                applyBtn.prop('disabled', false);
                applyBtn.html(originalText);

                // Show success message
                show_toastr('{{__("Success")}}', '{{__("Basic salary and all components have been applied successfully!")}}', 'success');

                // Scroll to basic salary section
                $('html, body').animate({
                    scrollTop: $('#section-base').offset().top - 100
                }, 500);

                // Flash the input
                $('#basic_salary').addClass('flash-increase');
                setTimeout(function() {
                    $('#basic_salary').removeClass('flash-increase');
                }, 600);

                // Reload page to show new components
                setTimeout(function() {
                    location.reload();
                }, 1500);
            })
            .catch(function(error) {
                // Re-enable button
                applyBtn.prop('disabled', false);
                applyBtn.html(originalText);

                console.error('Error applying components:', error);
                show_toastr('{{__("Error")}}', '{{__("Failed to apply some components. Please try again.")}}', 'error');
            });
    });

    // Apply All Components (Allowances + Deductions) from Payslip Type
    $('#apply-all-components-btn').on('click', function() {
        const selectedPayslipType = $('#salary_type').val();
        const employeeId = {{ $employee->id }};

        if (!selectedPayslipType) {
            show_toastr('{{__("Error")}}', '{{__("Please select a Payslip Type first")}}', 'error');
            // Scroll to payslip type field
            $('html, body').animate({
                scrollTop: $('#salary_type').offset().top - 100
            }, 500);
            $('#salary_type').focus();
            return;
        }

        const btn = $(this);
        const originalHtml = btn.html();
        btn.prop('disabled', true).html('<i class="ti ti-loader ti-spin"></i> {{__("Applying...")}}');

        let messages = [];
        let hasError = false;

        // First apply allowances
        $.ajax({
            url: '{{ route("setsalary.apply-allowances") }}',
            type: 'POST',
            data: {
                _token: csrfToken,
                employee_id: employeeId,
                payslip_type_id: selectedPayslipType
            },
            success: function(allowanceResponse) {
                console.log('Allowances Response:', allowanceResponse);
                if (allowanceResponse.success) {
                    messages.push('✓ ' + allowanceResponse.message);
                } else {
                    messages.push('✗ ' + allowanceResponse.message);
                    hasError = true;
                }

                // Then apply deductions
                $.ajax({
                    url: '{{ route("setsalary.apply-deductions") }}',
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        employee_id: employeeId,
                        payslip_type_id: selectedPayslipType
                    },
                    success: function(deductionResponse) {
                        console.log('Deductions Response:', deductionResponse);
                        if (deductionResponse.success) {
                            messages.push('✓ ' + deductionResponse.message);
                        } else {
                            messages.push('✗ ' + deductionResponse.message);
                            hasError = true;
                        }

                        // Show combined message
                        const combinedMessage = messages.join('<br>');
                        show_toastr(hasError ? '{{__("Warning")}}' : '{{__("Success")}}', combinedMessage, hasError ? 'warning' : 'success');

                        // Reload page to show all new components
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    },
                    error: function(xhr) {
                        console.error('Deductions Error:', xhr);
                        show_toastr('{{__("Error")}}', '{{__("An error occurred while applying deductions: ")}}' + (xhr.responseJSON?.message || xhr.statusText), 'error');
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            },
            error: function(xhr) {
                console.error('Allowances Error:', xhr);
                show_toastr('{{__("Error")}}', '{{__("An error occurred while applying allowances: ")}}' + (xhr.responseJSON?.message || xhr.statusText), 'error');
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
});
</script>
@endpush
