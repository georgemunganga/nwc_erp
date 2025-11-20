    {{Form::open(array('url'=>'deductionoption','method'=>'post' , 'class'=>'needs-validation', 'novalidate'))}}
    <div class="modal-body">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('name',__('Name'),['class'=>'form-label'])}}<x-required></x-required>
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Deduction Option Name'),'required'=> 'required'))}}
                @error('name')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::select('type', ['fixed' => __('Fixed'), 'percentage' => __('Percentage'), 'range' => __('Range')], 'fixed', ['class' => 'form-control', 'required' => 'required', 'id' => 'deduction-amount-type']) }}
                <div class="text-xs mt-1">
                    {{ __('Select the type of deduction: Fixed amount, Percentage of salary, or Range.') }}
                </div>
            </div>
        </div>

        <div class="col-md-12" id="deduction-amount-field">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Amount'), 'step' => '0.01', 'min' => '0', 'id' => 'deduction-amount-input']) }}
                <div class="text-xs mt-1">
                    {{ __('Enter the fixed amount or percentage value.') }}
                </div>
            </div>
        </div>

        <div class="col-md-6" id="deduction-min-amount-field" style="display: none;">
            <div class="form-group">
                {{ Form::label('min_amount', __('Minimum Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('min_amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Min Amount'), 'step' => '0.01', 'min' => '0', 'id' => 'deduction-min-amount-input']) }}
            </div>
        </div>

        <div class="col-md-6" id="deduction-max-amount-field" style="display: none;">
            <div class="form-group">
                {{ Form::label('max_amount', __('Maximum Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('max_amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Max Amount'), 'step' => '0.01', 'min' => '0', 'id' => 'deduction-max-amount-input']) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('tax_slabs', __('Tax Slabs (Optional)'), ['class' => 'form-label']) }}
                {{ Form::select('tax_slabs[]', $tax_slabs->pluck('name', 'id'), old('tax_slabs', []), ['class' => 'form-control select2', 'multiple' => 'multiple', 'id' => 'tax-slabs-select']) }}
                <div class="text-xs mt-1 text-muted">
                    {{ __('Optionally choose specific tax slabs that apply when using this deduction option.') }}
                </div>
            </div>
        </div>

    </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-secondary" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
    </div>
    {{Form::close()}}

<script>
    $(document).ready(function() {
        function toggleDeductionAmountFields() {
            var type = $('#deduction-amount-type').val();

            if (type === 'range') {
                $('#deduction-amount-field').hide();
                $('#deduction-amount-input').prop('required', false);
                $('#deduction-min-amount-field').show();
                $('#deduction-max-amount-field').show();
                $('#deduction-min-amount-input').prop('required', true);
                $('#deduction-max-amount-input').prop('required', true);
            } else {
                $('#deduction-amount-field').show();
                $('#deduction-amount-input').prop('required', true);
                $('#deduction-min-amount-field').hide();
                $('#deduction-max-amount-field').hide();
                $('#deduction-min-amount-input').prop('required', false);
                $('#deduction-max-amount-input').prop('required', false);
            }
        }

        $('#deduction-amount-type').on('change', toggleDeductionAmountFields);
        toggleDeductionAmountFields();
    });
</script>
