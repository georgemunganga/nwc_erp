@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Loan') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Loan') }}</li>
@endsection

@section('action-btn')
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @include('layouts.hrm_setup')
        </div>

        <!-- Filter Section -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['loan.index'], 'method' => 'get', 'id' => 'loan_filter']) }}
                    <div class="row align-items-center justify-content-end">
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('branch_id', __('Branch'), ['class' => 'form-label']) }}
                                {{ Form::select('branch_id', $branches, request('branch_id'), ['class' => 'form-control', 'placeholder' => __('Select Branch')]) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('department_id', __('Department'), ['class' => 'form-label']) }}
                                {{ Form::select('department_id', $departments, request('department_id'), ['class' => 'form-control', 'placeholder' => __('Select Department')]) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('designation_id', __('Designation'), ['class' => 'form-label']) }}
                                {{ Form::select('designation_id', $designations, request('designation_id'), ['class' => 'form-control', 'placeholder' => __('Select Designation')]) }}
                            </div>
                        </div>
                        <div class="col-auto mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-filter"></i> {{ __('Filter') }}
                            </button>
                            <a href="{{ route('loan.index') }}" class="btn btn-secondary">
                                <i class="ti ti-refresh"></i> {{ __('Reset') }}
                            </a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <!-- Loans Table -->
        <div class="col-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Employee') }}</th>
                                            <th>{{ __('Loan Option') }}</th>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Loan Amount') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Start Date') }}</th>
                                            <th>{{ __('End Date') }}</th>
                                            <th>{{ __('Reason') }}</th>
                                            <th width="200px">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="font-style">
                                        @foreach ($loans as $loan)
                                            <tr>
                                                <td>{{ !empty($loan->employee) ? $loan->employee->name : '' }}</td>
                                                <td>{{ !empty($loan->loanOption) ? $loan->loanOption->name : '' }}</td>
                                                <td>{{ $loan->title }}</td>
                                                <td>{{ \Auth::user()->priceFormat($loan->amount) }}</td>
                                                <td>{{ !empty($loan->type) ? ucfirst($loan->type) : '-' }}</td>
                                                <td>{{ !empty($loan->start_date) ? \Auth::user()->dateFormat($loan->start_date) : '-' }}</td>
                                                <td>{{ !empty($loan->end_date) ? \Auth::user()->dateFormat($loan->end_date) : '-' }}</td>
                                                <td>{{ $loan->reason }}</td>
                                                <td>
                                                    @can('edit loan')
                                                        <div class="action-btn me-2">
                                                            <a href="#" class="mx-3 btn btn-sm align-items-center bg-info"
                                                                data-url="{{ URL::to('loan/' . $loan->id . '/edit') }}"
                                                                data-ajax-popup="true"
                                                                data-title="{{ __('Edit Loan') }}"
                                                                data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                data-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('delete loan')
                                                        <div class="action-btn ">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['loan.destroy', $loan->id],
                                                                'id' => 'delete-form-' . $loan->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para bg-danger"
                                                                data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
