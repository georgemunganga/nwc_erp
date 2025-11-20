{{ Form::open(['route' => 'taxslab.store', 'method' => 'POST', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Tax Band Name'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter tax band name')]) }}
            @error('name')
            <small class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('min_salary', __('Minimum Chargeable Income'), ['class' => 'form-label']) }}
            {{ Form::number('min_salary', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0']) }}
            @error('min_salary')
            <small class="invalid-min_salary" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('max_salary', __('Maximum Chargeable Income'), ['class' => 'form-label']) }}
            {{ Form::number('max_salary', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0']) }}
            @error('max_salary')
            <small class="invalid-max_salary" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('rate', __('Tax Rate (%)'), ['class' => 'form-label']) }}<x-required></x-required>
            {{ Form::number('rate', null, ['class' => 'form-control', 'required' => 'required', 'min' => '0', 'max' => '100', 'step' => '0.01', 'placeholder' => __('Enter tax rate')]) }}
            @error('rate')
            <small class="invalid-rate" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
