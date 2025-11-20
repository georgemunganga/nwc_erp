    {{Form::model($deductionoption,array('route' => array('deductionoption.update', $deductionoption->id), 'method' => 'PUT' , 'class'=>'needs-validation', 'novalidate')) }}
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
                {{ Form::select('type', ['fixed' => __('Fixed'), 'percentage' => __('Percentage'), 'range' => __('Range')], null, ['class' => 'form-control', 'required' => 'required', 'id' => 'deduction-amount-type-edit']) }}
                <div class="text-xs mt-1">
                    {{ __('Select the type of deduction: Fixed amount, Percentage of salary, or Range.') }}
                </div>
            </div>
        </div>

        <div class="col-md-12" id="deduction-amount-field-edit">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Amount'), 'step' => '0.01', 'min' => '0', 'id' => 'deduction-amount-input-edit']) }}
                <div class="text-xs mt-1">
                    {{ __('Enter the fixed amount or percentage value.') }}
                </div>
            </div>
        </div>

        <div class="col-md-6" id="deduction-min-amount-field-edit" style="display: none;">
            <div class="form-group">
                {{ Form::label('min_amount', __('Minimum Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('min_amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Min Amount'), 'step' => '0.01', 'min' => '0', 'id' => 'deduction-min-amount-input-edit']) }}
            </div>
        </div>

        <div class="col-md-6" id="deduction-max-amount-field-edit" style="display: none;">
            <div class="form-group">
                {{ Form::label('max_amount', __('Maximum Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('max_amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Max Amount'), 'step' => '0.01', 'min' => '0', 'id' => 'deduction-max-amount-input-edit']) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('tax_slabs', __('Tax Slabs (Optional)'), ['class' => 'form-label']) }}
                {{ Form::select('tax_slabs[]', $tax_slabs->pluck('name', 'id'), old('tax_slabs', $selected_tax_slabs ?? []), ['class' => 'form-control select2', 'multiple' => 'multiple', 'id' => 'tax-slabs-select-edit']) }}
                <div class="text-xs mt-1 text-muted">
                    {{ __('Optionally choose specific tax slabs that apply when using this deduction option.') }}
                </div>
            </div>
        </div>

    </div>
    </div>

    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn btn-primary">
    </div>
    {{Form::close()}}

<script>
    $(document).ready(function() {
        function toggleDeductionAmountFieldsEdit() {
            var type = $('#deduction-amount-type-edit').val();

            if (type === 'range') {
                $('#deduction-amount-field-edit').hide();
                $('#deduction-amount-input-edit').prop('required', false);
                $('#deduction-min-amount-field-edit').show();
                $('#deduction-max-amount-field-edit').show();
                $('#deduction-min-amount-input-edit').prop('required', true);
                $('#deduction-max-amount-input-edit').prop('required', true);
            } else {
                $('#deduction-amount-field-edit').show();
                $('#deduction-amount-input-edit').prop('required', true);
                $('#deduction-min-amount-field-edit').hide();
                $('#deduction-max-amount-field-edit').hide();
                $('#deduction-min-amount-input-edit').prop('required', false);
                $('#deduction-max-amount-input-edit').prop('required', false);
            }
        }

        $('#deduction-amount-type-edit').on('change', toggleDeductionAmountFieldsEdit);
        toggleDeductionAmountFieldsEdit();
    });
</script>
