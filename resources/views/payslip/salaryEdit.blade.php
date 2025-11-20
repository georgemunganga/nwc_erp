<div class="col-form-label">
    <div class="px-3 row">
        <div class="mb-3 col-md-3">
            <h6 class="mb-2 emp-title">{{ __('Employee') }}</h6>
            <h6 class="emp-title black-text">
                {{ !empty($payslip->employees) ? \Auth::user()->employeeIdFormat($payslip->employees->employee_id) : '' }}
            </h6>
        </div>
        <div class="mb-3 col-md-3">
            <h6 class="mb-2 emp-title">{{ __('Basic Salary') }}</h6>
            <h6 class="emp-title black-text">{{ \Auth::user()->priceFormat($payslip->basic_salary) }}</h6>
        </div>
        <div class="mb-3 col-md-3">
            <h6 class="mb-2 emp-title">{{ __('Payroll Month') }}</h6>
            <h6 class="emp-title black-text">{{ \Auth::user()->dateFormat($payslip->salary_month) }}</h6>
        </div>
        <div class="mb-3 col-md-3 text-end">
            <button type="button" id="apply-components-btn" class="btn btn-primary btn-sm">
                <i class="ti ti-check"></i> {{ __('Apply Salary Components') }}
            </button>
        </div>
    </div>

    @if(isset($allowanceOptions) && count($allowanceOptions) > 0 || isset($deductionOptions) && count($deductionOptions) > 0)
    <div class="px-3 mb-3">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="ti ti-info-circle me-2"></i>
            <strong>{{ __('Quick Apply:') }}</strong>
            {{ __('Click "Apply Salary Components" button to automatically populate earnings and deductions from your payslip type configuration.') }}
            @if(isset($allowanceOptions) && count($allowanceOptions) > 0)
                <br><small>{{ __('Available allowances:') }} {{ $allowanceOptions->pluck('name')->implode(', ') }}</small>
            @endif
            @if(isset($deductionOptions) && count($deductionOptions) > 0)
                <br><small>{{ __('Available deductions:') }} {{ $deductionOptions->pluck('name')->implode(', ') }}</small>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @elseif(isset($employee) && !$employee->salary_type)
    <div class="px-3 mb-3">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>
            <strong>{{ __('No Payslip Type Assigned:') }}</strong>
            {{ __('This employee does not have a payslip type assigned. Please assign a payslip type in the employee settings to use the Quick Apply feature.') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

        <div class="col-lg-12 our-system">
            {{ Form::open(['route' => ['payslip.updateemployee', $payslip->employee_id], 'method' => 'post']) }}
            {!! Form::hidden('payslip_id', $payslip->id, ['class' => 'form-control']) !!}
            <div class="row">

                <ul class="gap-1 mb-3 nav nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#allowance"
                            role="tab" aria-controls="pills-home" aria-selected="true">{{ __('Allowance') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#commission"
                            role="tab" aria-controls="pills-profile"
                            aria-selected="false">{{ __('Commission') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" href="#loan" role="tab"
                            aria-controls="pills-contact" aria-selected="false">{{ __('Loan') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" href="#deduction"
                            role="tab" aria-controls="pills-contact"
                            aria-selected="false">{{ __('Saturation Deduction') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" href="#payment" role="tab"
                            aria-controls="pills-contact" aria-selected="false">{{ __('Other Payment') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" href="#overtime" role="tab"
                            aria-controls="pills-contact" aria-selected="false">{{ __('Overtime') }}</a>
                    </li>
                </ul>
                <div class="pt-4 tab-content">
                    <div id="allowance" class="tab-pane in active">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-0 card bg-none">
                                    <div class="px-3 row">
                                        @php
                                            $allowances = json_decode($payslip->allowance);
                                        @endphp
                                        @foreach ($allowances as $allownace)
                                            <div class="col-md-12 form-group">
                                                {!! Form::label('title', $allownace->title, ['class' => 'col-form-label']) !!}
                                                {!! Form::text('allowance[]', $allownace->amount, [
                                                    'class' => 'form-control',
                                                    'placeholder' => __($allownace->title),
                                                ]) !!}
                                                {!! Form::hidden('allowance_id[]', $allownace->id, ['class' => 'form-control']) !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="commission" class="tab-pane">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-0 card bg-none">
                                    <div class="px-3 row">
                                        @php
                                            $commissions = json_decode($payslip->commission);
                                        @endphp
                                        @foreach ($commissions as $commission)
                                            <div class="col-md-12 form-group">
                                                {!! Form::label('title', $commission->title, ['class' => 'col-form-label']) !!}
                                                {!! Form::text('commission[]', $commission->amount, [
                                                    'class' => 'form-control',
                                                    'placeholder' => __($commission->title),
                                                ]) !!}
                                                {!! Form::hidden('commission_id[]', $commission->id, ['class' => 'form-control']) !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="loan" class="tab-pane">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-0 card bg-none">
                                    <div class="px-3 row">
                                        @php
                                            $loans = json_decode($payslip->loan);
                                        @endphp
                                        @foreach ($loans as $loan)
                                            <div class="col-md-12 form-group">
                                                {!! Form::label('title', $loan->title, ['class' => 'col-form-label']) !!}
                                                {!! Form::text('loan[]', $loan->amount, ['class' => 'form-control', 'placeholder' => __($loan->title)]) !!}
                                                {!! Form::hidden('loan_id[]', $loan->id, ['class' => 'form-control']) !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="deduction" class="tab-pane">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-0 card bg-none">
                                    <div class="px-3 row">
                                        @php
                                            $saturation_deductions = json_decode($payslip->saturation_deduction);
                                        @endphp
                                        @foreach ($saturation_deductions as $deduction)
                                            <div class="col-md-12 form-group">
                                                {!! Form::label('title', $deduction->title, ['class' => 'col-form-label']) !!}
                                                {!! Form::text('saturation_deductions[]', $deduction->amount, [
                                                    'class' => 'form-control',
                                                    'placeholder' => __($deduction->title),
                                                ]) !!}
                                                {!! Form::hidden('saturation_deductions_id[]', $deduction->id, ['class' => 'form-control']) !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="payment" class="tab-pane">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-0 card bg-none">
                                    <div class="px-3 row">
                                        @php
                                            $other_payments = json_decode($payslip->other_payment);
                                        @endphp
                                        @foreach ($other_payments as $payment)
                                            <div class="col-md-12 form-group">
                                                {!! Form::label('title', $payment->title, ['class' => 'col-form-label']) !!}
                                                {!! Form::text('other_payment[]', $payment->amount, [
                                                    'class' => 'form-control',
                                                    'placeholder' => __($payment->title),
                                                ]) !!}
                                                {!! Form::hidden('other_payment_id[]', $payment->id, ['class' => 'form-control']) !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="overtime" class="tab-pane">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-0 card bg-none">
                                    <div class="px-3 row">
                                        @php
                                            $overtimes = json_decode($payslip->overtime);
                                        @endphp
                                        @foreach ($overtimes as $overtime)
                                            <div class="col-md-6 form-group">
                                                {!! Form::label('rate', $overtime->title . ' ' . __('Rate'), ['class' => 'col-form-label']) !!}
                                                {!! Form::text('rate[]', $overtime->rate, [
                                                    'class' => 'form-control',
                                                    'placeholder' => __($overtime->title . ' ' . __('Rate')),
                                                ]) !!}
                                                {!! Form::hidden('rate_id[]', $overtime->id, ['class' => 'form-control']) !!}
                                            </div>
                                            <div class="col-md-6 form-group">
                                                {!! Form::label('hours', $overtime->title . ' ' . __('Hours'), ['class' => 'col-form-label']) !!}
                                                {!! Form::text('hours[]', $overtime->hours, [
                                                    'class' => 'form-control',
                                                    'placeholder' => __($overtime->title . ' ' . __('Hours')),
                                                ]) !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary"
                    data-bs-dismiss="modal">
                <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const applyBtn = document.getElementById('apply-components-btn');

    if (applyBtn) {
        applyBtn.addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;

            // Disable button and show loading
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader ti-spin"></i> {{ __("Applying...") }}';

            // Make AJAX call to apply salary components
            $.ajax({
                url: '{{ route("payslip.apply-components") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    employee_id: '{{ $payslip->employee_id }}',
                    payslip_id: '{{ $payslip->id }}'
                },
                success: function(response) {
                    if (response.success) {
                        show_toastr('{{ __("Success") }}', response.message, 'success');

                        // Reload the modal content after a short delay
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        show_toastr('{{ __("Error") }}', response.message, 'error');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                },
                error: function(xhr) {
                    show_toastr('{{ __("Error") }}', '{{ __("An error occurred while applying components") }}', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            });
        });
    }
});
</script>
