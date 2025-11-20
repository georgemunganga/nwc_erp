<?php

namespace App\Http\Controllers;

use App\Models\PayslipType;
use App\Models\TaxSlab;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayslipTypeController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage payslip type'))
        {
            $paysliptypes = PayslipType::where('created_by', '=', \Auth::user()->creatorId())->with('taxSlabs')->get();

            return view('paysliptype.index', compact('paysliptypes'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create payslip type'))
        {
            $tax_slabs = TaxSlab::where('created_by', \Auth::user()->creatorId())->get();
            $allowance_options = \App\Models\AllowanceOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $deduction_options = \App\Models\DeductionOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('paysliptype.create', compact('tax_slabs', 'allowance_options', 'deduction_options'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        if(\Auth::user()->can('create payslip type'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'tax_slabs' => 'nullable|array',
                                   'tax_slabs.*' => [
                                       Rule::exists('tax_slabs', 'id')->where('created_by', \Auth::user()->creatorId()),
                                   ],
                                   'allowance_options' => 'nullable|array',
                                   'deduction_options' => 'nullable|array',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $paysliptype             = new PayslipType();
            $paysliptype->name       = $request->name;
            $paysliptype->created_by = \Auth::user()->creatorId();
            $paysliptype->save();

            $this->syncTaxSlabs($paysliptype, $request->input('tax_slabs', []));
            $paysliptype->allowanceOptions()->sync($request->input('allowance_options', []));
            $paysliptype->deductionOptions()->sync($request->input('deduction_options', []));

            return redirect()->route('paysliptype.index')->with('success', __('PayslipType  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(PayslipType $paysliptype)
    {
        return redirect()->route('paysliptype.index');
    }

    public function edit(PayslipType $paysliptype)
    {
        if(\Auth::user()->can('edit payslip type'))
        {
            if($paysliptype->created_by == \Auth::user()->creatorId())
            {
                try {
                    $tax_slabs = TaxSlab::where('created_by', \Auth::user()->creatorId())->get();
                    $selected_tax_slabs = $paysliptype->taxSlabs()->pluck('id')->toArray();

                    $allowance_options = \App\Models\AllowanceOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                    $deduction_options = \App\Models\DeductionOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

                    $selected_allowances = $paysliptype->allowanceOptions()->pluck('allowance_options.id')->toArray();
                    $selected_deductions = $paysliptype->deductionOptions()->pluck('deduction_options.id')->toArray();

                    return view('paysliptype.edit', compact('paysliptype', 'tax_slabs', 'selected_tax_slabs', 'allowance_options', 'deduction_options', 'selected_allowances', 'selected_deductions'));
                } catch (\Exception $e) {
                    \Log::error('PayslipType Edit Error: ' . $e->getMessage());
                    return response()->json(['error' => __('Error loading data: ') . $e->getMessage()], 500);
                }
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, PayslipType $paysliptype)
    {
        if(\Auth::user()->can('edit payslip type'))
        {
            if($paysliptype->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                       'tax_slabs' => 'nullable|array',
                                       'tax_slabs.*' => [
                                           Rule::exists('tax_slabs', 'id')->where('created_by', \Auth::user()->creatorId()),
                                       ],
                                       'allowance_options' => 'nullable|array',
                                       'deduction_options' => 'nullable|array',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $paysliptype->name = $request->name;
                $paysliptype->save();

                $this->syncTaxSlabs($paysliptype, $request->input('tax_slabs', []));
                $paysliptype->allowanceOptions()->sync($request->input('allowance_options', []));
                $paysliptype->deductionOptions()->sync($request->input('deduction_options', []));

                return redirect()->route('paysliptype.index')->with('success', __('PayslipType successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(PayslipType $paysliptype)
    {
        if(\Auth::user()->can('delete payslip type'))
        {
            if($paysliptype->created_by == \Auth::user()->creatorId())
            {
                $paysliptype->delete();

                return redirect()->route('paysliptype.index')->with('success', __('PayslipType successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    protected function syncTaxSlabs(PayslipType $paysliptype, array $taxSlabIds)
    {
        $taxSlabIds = array_values(array_filter($taxSlabIds));
        $creatorId = \Auth::user()->creatorId();

        $detachQuery = TaxSlab::where('created_by', $creatorId)
            ->where('payslip_type_id', $paysliptype->id);

        if (!empty($taxSlabIds)) {
            $detachQuery->whereNotIn('id', $taxSlabIds);
        }

        $detachQuery->update(['payslip_type_id' => null]);

        if (!empty($taxSlabIds)) {
            TaxSlab::where('created_by', $creatorId)
                ->whereIn('id', $taxSlabIds)
                ->update(['payslip_type_id' => $paysliptype->id]);
        }
    }
}
