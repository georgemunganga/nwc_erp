{{ Form::open(array('url' => 'bank-account' , 'class'=>'needs-validation', 'novalidate')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('chart_account_id', __('Account'),['class'=>'form-label']) }}<x-required></x-required>
            <select name="chart_account_id" class="form-control" required>
                <option value="">{{ __('Select Chart of Account') }}</option>
                @foreach ($chartAccounts as $typeName => $subtypes)
                    <optgroup label="{{ $typeName }}">
                        @foreach ($subtypes as $subtypeId => $subtypeData)
                            <option disabled style="color: #000; font-weight: bold;">{{ $subtypeData['account_name'] }}</option>
                            @foreach ($subtypeData['chart_of_accounts'] as $chartOfAccount)
                                <option value="{{ $chartOfAccount['id'] }}">
                                    &nbsp;&nbsp;&nbsp;{{ $chartOfAccount['account_name'] }}
                                </option>
                                @foreach ($subtypeData['subAccounts'] as $subAccount)
                                    @if ($chartOfAccount['id'] == $subAccount['parent_account'])
                                    <option value="{{ $subAccount['id'] }}" class="ms-5"> &nbsp; &nbsp;&nbsp;&nbsp; {{' - '. $subAccount['account_name'] }}</option>
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <div class="text-xs mt-1">
                {{ __('Create account here.') }} <a href="{{ route('chart-of-account.index') }}"><b>{{ __('Create account') }}</b></a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">{{ __('Is Company Account') }}</label>
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="is_company_account" name="is_company_account" value="1" checked onchange="toggleAccountType()">
                    <label class="form-check-label" for="is_company_account">{{ __('Yes') }}</label>
                </div>
            </div>
        </div>
        <div class="col-md-6" id="account_type_wrapper" style="display: none;">
            <div class="form-group">
                {{ Form::label('account_type', __('Account Type'), ['class' => 'form-label']) }}<x-required></x-required>
                <select name="account_type" id="account_type" class="form-control">
                    <option value="">{{ __('Select Account Type') }}</option>
                    <option value="customer">{{ __('Customer') }}</option>
                    <option value="vendor">{{ __('Vendor') }}</option>
                    <option value="shareholder">{{ __('Shareholder') }}</option>
                    <option value="employee">{{ __('Employee') }}</option>
                </select>
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('holder_name', __('Bank Holder Name'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::text('holder_name', '', array('class' => 'form-control','required'=>'required' , 'placeholder'=>__('Enter Bank Holder Name'))) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('bank_name', __('Bank Name'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::text('bank_name', '', array('class' => 'form-control','required'=>'required' , 'placeholder' => __('Enter Bank Name'))) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('account_number', __('Account Number'),['class'=>'form-label']) }}<x-required></x-required>
            {{ Form::text('account_number', '', array('class' => 'form-control','required'=>'required' , 'placeholder'=>__('Enter Account Number'))) }}
        </div>
        <div class="col-md-6" id="payment_gateway_wrapper">
            <div class="form-group">
                {{ Form::label('payment_name', __('Payment Gateway'), ['class' => 'form-label']) }}<x-required></x-required>
                <select name="payment_name" id="payment_name" class="form-control" required="required">
                    <option value="" disabled selected>{{ __('Select Type') }}</option>
                    @foreach ($payments as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('opening_balance', __('Opening Balance'),['class'=>'form-label']) }}
            {{ Form::number('opening_balance', '', array('class' => 'form-control','step'=>'0.01' , 'placeholder'=>__('Enter Opening Balance'))) }}
        </div>
        <div class="col-md-6">
            <x-Mobile name="contact_number" label="{{__('Contact Number')}}" placeholder="{{__('Enter Contact Number')}}"></x-Mobile>
        </div>
        <div class="form-group col-md-12 mb-0">
            {{ Form::label('bank_address', __('Bank Address'),['class'=>'form-label']) }}
            {{ Form::textarea('bank_address', '', array('class' => 'form-control','rows'=>3 , 'placeholder'=>__('Enter Bank Address'))) }}
        </div>
        @if(!$customFields->isEmpty())
                    @include('customFields.formBuilder')
        @endif

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}

<script>
function toggleAccountType() {
    const isCompanyAccount = document.getElementById('is_company_account').checked;
    const accountTypeWrapper = document.getElementById('account_type_wrapper');
    const accountTypeSelect = document.getElementById('account_type');
    const paymentGatewayWrapper = document.getElementById('payment_gateway_wrapper');
    const paymentNameSelect = document.getElementById('payment_name');

    if (isCompanyAccount) {
        // Company account - hide account type, show payment gateway
        accountTypeWrapper.style.display = 'none';
        accountTypeSelect.removeAttribute('required');
        accountTypeSelect.value = '';
        paymentGatewayWrapper.style.display = 'block';
        paymentNameSelect.setAttribute('required', 'required');
    } else {
        // Not company account - show account type, hide payment gateway
        accountTypeWrapper.style.display = 'block';
        accountTypeSelect.setAttribute('required', 'required');
        paymentGatewayWrapper.style.display = 'none';
        paymentNameSelect.removeAttribute('required');
        paymentNameSelect.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleAccountType();
});
</script>
