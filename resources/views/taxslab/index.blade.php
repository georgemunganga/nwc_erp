@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Tax Slabs') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Tax Slabs') }}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        @can('manage set salary')
            <a href="#" data-url="{{ route('taxslab.create') }}" data-size="md" data-ajax-popup="true"
                data-title="{{ __('Create Tax Slab') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i> {{ __('Create Tax Slab') }}
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable" id="taxslab-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Tax Band') }}</th>
                                    <th>{{ __('Chargeable Income') }}</th>
                                    <th>{{ __('Tax Rate (%)') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th width="120px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tax_slabs as $tax_slab)
                                    @php
                                        $chargeable = null;
                                        if ($tax_slab->min_salary && $tax_slab->max_salary) {
                                            $chargeable = \Auth::user()->priceFormat($tax_slab->min_salary) . ' - ' . \Auth::user()->priceFormat($tax_slab->max_salary);
                                        } elseif ($tax_slab->min_salary) {
                                            $chargeable = __(':value and above', ['value' => \Auth::user()->priceFormat($tax_slab->min_salary)]);
                                        } elseif ($tax_slab->max_salary) {
                                            $chargeable = __('Up to :value', ['value' => \Auth::user()->priceFormat($tax_slab->max_salary)]);
                                        }
                                        if ($tax_slab->min_salary && $tax_slab->max_salary) {
                                            $description = __('Any amount between :min and :max', [
                                                'min' => \Auth::user()->priceFormat($tax_slab->min_salary),
                                                'max' => \Auth::user()->priceFormat($tax_slab->max_salary),
                                            ]);
                                        } elseif ($tax_slab->min_salary) {
                                            $description = __('Any amount equal to or above :min', ['min' => \Auth::user()->priceFormat($tax_slab->min_salary)]);
                                        } elseif ($tax_slab->max_salary) {
                                            $description = __('Any amount up to :max', ['max' => \Auth::user()->priceFormat($tax_slab->max_salary)]);
                                        } else {
                                            $description = __('No range specified');
                                        }
                                    @endphp
                                    <tr class="font-style">
                                        <td>{{ $tax_slab->name }}</td>
                                        <td>{{ $chargeable ?? '-' }}</td>
                                        <td>{{ $tax_slab->rate }}%</td>
                                        <td>{{ $description }}</td>
                                        <td class="Action">
                                            @can('manage set salary')
                                                <span>
                                                    <div class="action-btn me-2">
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bg-info"
                                                            data-url="{{ route('taxslab.edit', $tax_slab->id) }}"
                                                            data-ajax-popup="true"
                                                            data-title="{{ __('Edit Tax Slab') }}"
                                                            data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                    <div class="action-btn">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['taxslab.destroy', $tax_slab->id], 'id' => 'destroy-tax-slab-' . $tax_slab->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para bg-danger"
                                                            data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                            data-confirm="{{ __('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="document.getElementById('destroy-tax-slab-{{ $tax_slab->id }}').submit();">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                </span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="mb-3">{{ __('Sample Tax Bands') }}</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Tax Bands') }}</th>
                                    <th>{{ __('Chargeable Income') }}</th>
                                    <th>{{ __('Tax Rate') }}</th>
                                    <th>{{ __('Description') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ __('First K5,100.00') }}</td>
                                    <td>@</td>
                                    <td>00%</td>
                                    <td>{{ __('Any income up to :value', ['value' => __('K5,100.00')]) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Next K5,100.01 but not exceeding K7,100.00') }}</td>
                                    <td>=</td>
                                    <td>20%</td>
                                    <td>{{ __('Any amount between :min and :max', ['min' => __('K5,100.01'), 'max' => __('K7,100.00')]) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Next K7,100.01 but not exceeding K9,200.00') }}</td>
                                    <td>=</td>
                                    <td>30%</td>
                                    <td>{{ __('Any amount between :min and :max', ['min' => __('K7,100.01'), 'max' => __('K9,200.00')]) }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('K9,200.01 and Above') }}</td>
                                    <td>{{ __('0') }}</td>
                                    <td>37%</td>
                                    <td>{{ __('Any income above :min', ['min' => __('K9,200.01')]) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function () {
            $("#taxslab-table").dataTable({
                "columnDefs": [
                    { "sortable": false, "targets": [4] }
                ]
            });
        });
    </script>
@endpush
