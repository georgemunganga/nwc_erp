<?php

namespace App\Http\Controllers;

use App\Models\DeductionOption;
use App\Models\TaxSlab;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeductionOptionController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage deduction option'))
        {
            $deductionoptions = DeductionOption::where('created_by', '=', \Auth::user()->creatorId())->with('taxSlabs')->get();

            return view('deductionoption.index', compact('deductionoptions'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create deduction option'))
        {
            $tax_slabs = TaxSlab::where('created_by', \Auth::user()->creatorId())->get();
            return view('deductionoption.create', compact('tax_slabs'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create deduction option'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'type' => 'required|in:percentage,fixed,range',
                    'amount' => 'required_if:type,percentage,fixed|nullable|numeric|min:0',
                    'min_amount' => 'required_if:type,range|nullable|numeric|min:0',
                    'max_amount' => 'required_if:type,range|nullable|numeric|min:0|gt:min_amount',
                    'tax_slabs' => 'nullable|array',
                    'tax_slabs.*' => Rule::exists('tax_slabs', 'id')->where('created_by', \Auth::user()->creatorId()),
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $deductionoption             = new DeductionOption();
            $deductionoption->name       = $request->name;
            $deductionoption->type       = $request->type;
            $deductionoption->amount     = $request->type != 'range' ? $request->amount : null;
            $deductionoption->min_amount = $request->type == 'range' ? $request->min_amount : null;
            $deductionoption->max_amount = $request->type == 'range' ? $request->max_amount : null;
            $deductionoption->created_by = \Auth::user()->creatorId();
            $deductionoption->save();
            $this->syncTaxSlabs($deductionoption, $request->input('tax_slabs', []));

            return redirect()->route('deductionoption.index')->with('success', __('DeductionOption  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(DeductionOption $deductionoption)
    {
        return redirect()->route('deductionoption.index');
    }

    public function edit($deductionoption)
    {
        $deductionoption = DeductionOption::find($deductionoption);
        if(\Auth::user()->can('edit deduction option'))
        {
                if($deductionoption->created_by == \Auth::user()->creatorId())
                {
                    $tax_slabs = TaxSlab::where('created_by', \Auth::user()->creatorId())->get();
                    $selected_tax_slabs = $deductionoption->taxSlabs->pluck('id')->toArray();

                    return view('deductionoption.edit', compact('deductionoption', 'tax_slabs', 'selected_tax_slabs'));
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

    public function update(Request $request, DeductionOption $deductionoption)
    {
        if(\Auth::user()->can('edit deduction option'))
        {
            if($deductionoption->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                        'name' => 'required',
                        'type' => 'required|in:percentage,fixed,range',
                        'amount' => 'required_if:type,percentage,fixed|nullable|numeric|min:0',
                        'min_amount' => 'required_if:type,range|nullable|numeric|min:0',
                        'max_amount' => 'required_if:type,range|nullable|numeric|min:0|gt:min_amount',
                        'tax_slabs' => 'nullable|array',
                        'tax_slabs.*' => Rule::exists('tax_slabs', 'id')->where('created_by', \Auth::user()->creatorId()),
                    ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $deductionoption->name       = $request->name;
                $deductionoption->type       = $request->type;
                $deductionoption->amount     = $request->type != 'range' ? $request->amount : null;
                $deductionoption->min_amount = $request->type == 'range' ? $request->min_amount : null;
                $deductionoption->max_amount = $request->type == 'range' ? $request->max_amount : null;
                $deductionoption->save();
                $this->syncTaxSlabs($deductionoption, $request->input('tax_slabs', []));

                return redirect()->route('deductionoption.index')->with('success', __('DeductionOption successfully updated.'));
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

    protected function syncTaxSlabs(DeductionOption $deductionoption, array $taxSlabIds)
    {
        $taxSlabIds = array_values(array_filter($taxSlabIds));

        if (empty($taxSlabIds)) {
            $deductionoption->taxSlabs()->sync([]);
            return;
        }

        $deductionoption->taxSlabs()->sync($taxSlabIds);
    }

    public function destroy(DeductionOption $deductionoption)
    {
        if(\Auth::user()->can('delete deduction option'))
        {
            if($deductionoption->created_by == \Auth::user()->creatorId())
            {
                $deductionoption->delete();

                return redirect()->route('deductionoption.index')->with('success', __('DeductionOption successfully deleted.'));
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
}
