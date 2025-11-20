
    {{Form::open(array('url'=>'allowanceoption','method'=>'post' , 'class'=>'needs-validation', 'novalidate'))}}
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
                {{ Form::select('type', ['fixed' => __('Fixed'), 'percentage' => __('Percentage'), 'range' => __('Range')], 'fixed', ['class' => 'form-control', 'required' => 'required', 'id' => 'allowance-type']) }}
                <div class="text-xs mt-1">
                    {{ __('Select the type of allowance: Fixed amount, Percentage of salary, or Range.') }}
                </div>
            </div>
        </div>

        <div class="col-md-12" id="amount-field">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Amount'), 'step' => '0.01', 'min' => '0', 'id' => 'amount-input']) }}
                <div class="text-xs mt-1">
                    {{ __('Enter the fixed amount or percentage value.') }}
                </div>
            </div>
        </div>

        <div class="col-md-6" id="min-amount-field" style="display: none;">
            <div class="form-group">
                {{ Form::label('min_amount', __('Minimum Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('min_amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Min Amount'), 'step' => '0.01', 'min' => '0', 'id' => 'min-amount-input']) }}
            </div>
        </div>

        <div class="col-md-6" id="max-amount-field" style="display: none;">
            <div class="form-group">
                {{ Form::label('max_amount', __('Maximum Amount'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::number('max_amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Max Amount'), 'step' => '0.01', 'min' => '0', 'id' => 'max-amount-input']) }}
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
            function toggleAmountFields() {
                var type = $('#allowance-type').val();

                if (type === 'range') {
                    $('#amount-field').hide();
                    $('#amount-input').prop('required', false);
                    $('#min-amount-field').show();
                    $('#max-amount-field').show();
                    $('#min-amount-input').prop('required', true);
                    $('#max-amount-input').prop('required', true);
                } else {
                    $('#amount-field').show();
                    $('#amount-input').prop('required', true);
                    $('#min-amount-field').hide();
                    $('#max-amount-field').hide();
                    $('#min-amount-input').prop('required', false);
                    $('#max-amount-input').prop('required', false);
                }
            }

            $('#allowance-type').on('change', toggleAmountFields);
            toggleAmountFields();
        });
    </script>

