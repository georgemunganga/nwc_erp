    {{Form::open(array('url'=>'paysliptype','method'=>'post' , 'class'=>'needs-validation', 'novalidate'))}}
    <div class="modal-body">


    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('name',__('Name'),['class'=>'form-label'])}}<x-required></x-required>
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Payslip Type Name'),'required'=> 'required'))}}
                @error('name')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('tax_slabs', __('Tax Slabs'), ['class' => 'form-label']) }}
                {{ Form::select('tax_slabs[]', $tax_slabs->pluck('name', 'id'), old('tax_slabs', []), ['class' => 'form-control select select2', 'multiple' => 'multiple', 'id' => 'tax-slabs-create', 'data-placeholder' => __('Select Tax Slabs')]) }}
                <div class="text-xs mt-1">
                    {{ __('Select the tax slabs that are applicable for this payslip type.') }}
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('allowance_options', __('Allowance Components'), ['class' => 'form-label']) }}
                {{ Form::select('allowance_options[]', $allowance_options, old('allowance_options', []), ['class' => 'form-control select select2', 'multiple' => 'multiple', 'id' => 'allowance-options-create', 'data-placeholder' => __('Select Allowance Components')]) }}
                <div class="text-xs mt-1">
                    {{ __('Select the allowance components that will be included in this payslip type.') }}
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('deduction_options', __('Deduction Components'), ['class' => 'form-label']) }}
                {{ Form::select('deduction_options[]', $deduction_options, old('deduction_options', []), ['class' => 'form-control select select2', 'multiple' => 'multiple', 'id' => 'deduction-options-create', 'data-placeholder' => __('Select Deduction Components')]) }}
                <div class="text-xs mt-1">
                    {{ __('Select the deduction components that will be included in this payslip type.') }}
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
