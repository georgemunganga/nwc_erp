
    {{Form::model($allowanceoption,array('route' => array('allowanceoption.update', $allowanceoption->id), 'method' => 'PUT' , 'class'=>'needs-validation', 'novalidate')) }}
    <div class="modal-body">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('name',__('Name'),['class'=>'form-label'])}}<x-required></x-required>
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Allowance option Name'),'required'=> 'required'))}}
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
                {{ Form::select('type', ['fixed' => __('Fixed'), 'percentage' => __('Percentage'), 'range' => __('Range')], null, ['class' => 'form-control', 'required' => 'required', 'id' => 'allowance-type-edit']) }}
                <div class="text-xs mt-1">
                    {{ __('Select the type of allowance: Fixed amount, Percentage of salary, or Range.') }}
                </div>
            </div>
        </div>

        <div class="col-md-12" id="amount-field-edit">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Amount'), 'step' => '0.01', 'min' => '0', 'id' => 'amount-input-edit']) }}
                <div class="text-xs mt-1">
                    {{ __('Enter the fixed amount or percentage value.') }}
                </div>
            </div>
        </div>

        <div class="col-md-6" id="min-amount-field-edit" style="display: none;">
            <div class="form-group">
                {{ Form::label('min_amount', __('Minimum Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('min_amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Min Amount'), 'step' => '0.01', 'min' => '0', 'id' => 'min-amount-input-edit']) }}
            </div>
        </div>

        <div class="col-md-6" id="max-amount-field-edit" style="display: none;">
            <div class="form-group">
                {{ Form::label('max_amount', __('Maximum Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('max_amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Max Amount'), 'step' => '0.01', 'min' => '0', 'id' => 'max-amount-input-edit']) }}
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
            function toggleAmountFieldsEdit() {
                var type = $('#allowance-type-edit').val();

                if (type === 'range') {
                    $('#amount-field-edit').hide();
                    $('#amount-input-edit').prop('required', false);
                    $('#min-amount-field-edit').show();
                    $('#max-amount-field-edit').show();
                    $('#min-amount-input-edit').prop('required', true);
                    $('#max-amount-input-edit').prop('required', true);
                } else {
                    $('#amount-field-edit').show();
                    $('#amount-input-edit').prop('required', true);
                    $('#min-amount-field-edit').hide();
                    $('#max-amount-field-edit').hide();
                    $('#min-amount-input-edit').prop('required', false);
                    $('#max-amount-input-edit').prop('required', false);
                }
            }

            $('#allowance-type-edit').on('change', toggleAmountFieldsEdit);
            toggleAmountFieldsEdit();
        });
    </script>
