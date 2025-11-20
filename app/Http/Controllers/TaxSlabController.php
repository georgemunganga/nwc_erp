<?php

namespace App\Http\Controllers;

use App\Models\TaxSlab;
use Illuminate\Http\Request;

class TaxSlabController extends Controller
{
    protected function ownerQuery()
    {
        return TaxSlab::where('created_by', \Auth::user()->creatorId());
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0',
            'rate' => 'required|numeric|min:0|max:100',
        ];
    }

    public function index()
    {
        if (!\Auth::user()->can('manage set salary')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $tax_slabs = $this->ownerQuery()->orderBy('min_salary')->get();

        return view('taxslab.index', compact('tax_slabs'));
    }

    public function create()
    {
        if (!\Auth::user()->can('manage set salary')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        return view('taxslab.create');
    }

    public function store(Request $request)
    {
        if (!\Auth::user()->can('manage set salary')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $request->validate($this->rules());

        TaxSlab::create([
            'name' => $request->name,
            'min_salary' => $request->min_salary,
            'max_salary' => $request->max_salary,
            'rate' => $request->rate,
            'created_by' => \Auth::user()->creatorId(),
        ]);

        return redirect()->route('taxslab.index')->with('success', __('Tax slab created.'));
    }

    public function edit($id)
    {
        if (!\Auth::user()->can('manage set salary')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $tax_slab = $this->ownerQuery()->findOrFail($id);

        return view('taxslab.edit', compact('tax_slab'));
    }

    public function update(Request $request, $id)
    {
        if (!\Auth::user()->can('manage set salary')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $tax_slab = $this->ownerQuery()->findOrFail($id);
        $request->validate($this->rules());

        $tax_slab->update([
            'name' => $request->name,
            'min_salary' => $request->min_salary,
            'max_salary' => $request->max_salary,
            'rate' => $request->rate,
        ]);

        return redirect()->route('taxslab.index')->with('success', __('Tax slab updated.'));
    }

    public function destroy($id)
    {
        if (!\Auth::user()->can('manage set salary')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $taxSlab = $this->ownerQuery()->find($id);
        if (!$taxSlab) {
            return redirect()->back()->with('error', __('Tax slab not found.'));
        }
        $taxSlab->delete();

        return redirect()->route('taxslab.index')->with('success', __('Tax slab deleted.'));
    }
}
