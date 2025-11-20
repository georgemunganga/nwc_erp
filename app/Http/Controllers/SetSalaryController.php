<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\AllowanceOption;
use App\Models\Branch;
use App\Models\Commission;
use App\Models\Department;
use App\Models\DeductionOption;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanOption;
use App\Models\OtherPayment;
use App\Models\Overtime;
use App\Models\PayslipType;
use App\Models\BankAccount;
use App\Models\SaturationDeduction;
use App\Models\TaxSlab;
use Illuminate\Http\Request;

class SetSalaryController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage set salary'))
        {
            $employees = Employee::where(
                [
                    'created_by' => \Auth::user()->creatorId(),
                ]
            )->with('salaryType')->get();

            return view('setsalary.index', compact('employees'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function bulk(Request $request)
    {
        if(!\Auth::user()->can('manage set salary'))
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $branches = Branch::where('created_by', \Auth::user()->creatorId())->pluck('name', 'id');
        $departments = Department::where('created_by', \Auth::user()->creatorId())->pluck('name', 'id');
        $designations = Designation::where('created_by', \Auth::user()->creatorId())->pluck('name', 'id');
        $payslip_type = PayslipType::where('created_by', \Auth::user()->creatorId())->pluck('name', 'id');

        $employees = Employee::where('created_by', \Auth::user()->creatorId())
            ->with(['branch', 'department', 'designation', 'salaryType']);

        if($request->filled('branch'))
        {
            $employees->where('branch_id', $request->branch);
        }

        if($request->filled('department'))
        {
            $employees->where('department_id', $request->department);
        }

        if($request->filled('designation'))
        {
            $employees->where('designation_id', $request->designation);
        }

        $employees = $employees->get();

        return view('setsalary.bulk', compact('employees', 'branches', 'departments', 'designations', 'payslip_type'));
    }

    public function edit($id)
    {
        if(\Auth::user()->can('edit set salary'))
        {

            $payslip_type      = PayslipType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $allowance_options = AllowanceOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $loan_options      = LoanOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $deduction_options = DeductionOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            if(\Auth::user()->type == 'Employee')
            {
                $currentEmployee      = Employee::where('user_id', '=', \Auth::user()->id)->first();
                $allowances           = Allowance::where('employee_id', $currentEmployee->id)->get();
                $commissions          = Commission::where('employee_id', $currentEmployee->id)->get();
                $loans                = Loan::where('employee_id', $currentEmployee->id)->get();
                $saturationdeductions = SaturationDeduction::where('employee_id', $currentEmployee->id)->get();
                $otherpayments        = OtherPayment::where('employee_id', $currentEmployee->id)->get();
                $overtimes            = Overtime::where('employee_id', $currentEmployee->id)->get();
                $employee             = Employee::where('user_id', '=', \Auth::user()->id)->first();

                $tax_slabs = TaxSlab::where('created_by', \Auth::user()->creatorId())->get();
                return view('setsalary.employee_salary', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances', 'tax_slabs'));

            }
            else
            {
                $allowances           = Allowance::where('employee_id', $id)->get();
                $commissions          = Commission::where('employee_id', $id)->get();
                $loans                = Loan::where('employee_id', $id)->get();
                $saturationdeductions = SaturationDeduction::where('employee_id', $id)->get();
                $otherpayments        = OtherPayment::where('employee_id', $id)->get();
                $overtimes            = Overtime::where('employee_id', $id)->get();
                $employee             = Employee::find($id);

                $tax_slabs = TaxSlab::where('created_by', \Auth::user()->creatorId())->get();
                return view('setsalary.edit', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances', 'tax_slabs'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show($id)
    {
        $payslip_type      = PayslipType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $allowance_options = AllowanceOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $loan_options      = LoanOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $deduction_options = DeductionOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        if(\Auth::user()->type == 'Employee')
        {
            $currentEmployee      = Employee::where('user_id', '=', \Auth::user()->id)->first();
            $allowances           = Allowance::where('employee_id', $currentEmployee->id)->with(['employee','allowanceOption'])->get();
            $commissions          = Commission::where('employee_id', $currentEmployee->id)->with(['employee'])->get();
            $loans                = Loan::where('employee_id', $currentEmployee->id)->With(['employee','loanOption'])->get();
            $saturationdeductions = SaturationDeduction::where('employee_id', $currentEmployee->id)->get();
            $otherpayments        = OtherPayment::where('employee_id', $currentEmployee->id)->get();
            $overtimes            = Overtime::where('employee_id', $currentEmployee->id)->get();
            $employee             = Employee::where('user_id', '=', \Auth::user()->id)->first();

            foreach ($allowances as  $value) {
                if($value->type == 'percentage' )
                {
                    $employee          = Employee::find($value->employee_id);

                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ( $commissions as  $value) {
                if(  $value->type == 'percentage' )
                {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ( $loans as  $value) {
                if(  $value->type == 'percentage' )
                {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ( $saturationdeductions as  $value) {
                if(  $value->type == 'percentage' )
                {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ( $otherpayments as  $value) {
                if(  $value->type == 'percentage' )
                {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            $tax_slabs = TaxSlab::where('created_by', \Auth::user()->creatorId())->get();
            return view('setsalary.employee_salary', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances', 'tax_slabs'));


        }
        else
        {
            $allowances           = Allowance::where('employee_id', $id)->get();
            $commissions          = Commission::where('employee_id', $id)->get();
            $loans                = Loan::where('employee_id', $id)->get();
            $saturationdeductions = SaturationDeduction::where('employee_id', $id)->get();
            $otherpayments        = OtherPayment::where('employee_id', $id)->get();
            $overtimes            = Overtime::where('employee_id', $id)->get();
            $employee             = Employee::find($id);

            foreach ( $allowances as  $value) {
                if(  $value->type == 'percentage' )
                {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ( $commissions as  $value) {
                if(  $value->type == 'percentage' )
                {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ( $loans as  $value) {
                if(  $value->type == 'percentage' )
                {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ( $saturationdeductions as  $value) {
                if(  $value->type == 'percentage' )
                {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            foreach ( $otherpayments as  $value) {
                if(  $value->type == 'percentage' )
                {
                    $employee          = Employee::find($value->employee_id);
                    $empsal  = $value->amount * $employee->salary / 100;
                    $value->tota_allow = $empsal;
                }
            }

            $tax_slabs = TaxSlab::where('created_by', \Auth::user()->creatorId())->get();
            return view('setsalary.employee_salary', compact('employee', 'payslip_type', 'allowance_options', 'commissions', 'loan_options', 'overtimes', 'otherpayments', 'saturationdeductions', 'loans', 'deduction_options', 'allowances', 'tax_slabs'));
        }

    }


    public function employeeUpdateSalary(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'salary_type' => 'required',
                               'salary' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $employee = Employee::findOrFail($id);
        $input    = $request->all();
        $employee->fill($input)->save();

        return redirect()->back()->with('success', 'Employee Salary Updated.');
    }

    public function employeeSalary()
    {
        if(\Auth::user()->type == "employee")
        {
            $employees = Employee::where('user_id', \Auth::user()->id)->get();
            return view('setsalary.index', compact('employees'));
        }
    }

    public function employeeBasicSalary($id)
    {

        $payslip_type = PayslipType::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $account = BankAccount::where('created_by', \Auth::user()->creatorId())->where('holder_name' , '!=', 'cash')->get()->pluck('bank_name', 'id');

        $employee     = Employee::find($id);

        return view('setsalary.basic_salary', compact('employee', 'payslip_type' , 'account'));
    }

    public function applyAllowances(Request $request)
    {
        try {
            $employee = Employee::find($request->employee_id);
            $payslipType = PayslipType::find($request->payslip_type_id);

            if (!$employee || !$payslipType) {
                return response()->json([
                    'success' => false,
                    'message' => __('Employee or Payslip Type not found.')
                ]);
            }

            $allowanceOptions = $payslipType->allowanceOptions;

            if ($allowanceOptions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => __('No allowance options configured in this payslip type.')
                ]);
            }

            // Get existing allowances for this employee
            $existingAllowances = Allowance::where('employee_id', $employee->id)
                ->pluck('allowance_option')
                ->toArray();

            $appliedCount = 0;
            $appliedNames = [];

            // Apply allowances that don't already exist
            foreach ($allowanceOptions as $option) {
                if (!in_array($option->id, $existingAllowances)) {
                    $allowance = new Allowance();
                    $allowance->employee_id = $employee->id;
                    $allowance->allowance_option = $option->id;
                    $allowance->title = $option->name;
                    $allowance->amount = $option->amount ?? 0;
                    $allowance->created_by = \Auth::user()->creatorId();
                    $allowance->save();
                    $appliedCount++;
                    $appliedNames[] = $option->name;
                }
            }

            if ($appliedCount > 0) {
                return response()->json([
                    'success' => true,
                    'message' => __('Successfully applied :count allowances:', ['count' => $appliedCount]) . ' ' . implode(', ', $appliedNames)
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => __('All allowances from this payslip type are already applied.')
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('An error occurred: ') . $e->getMessage()
            ]);
        }
    }

    public function applyDeductions(Request $request)
    {
        try {
            $employee = Employee::find($request->employee_id);
            $payslipType = PayslipType::find($request->payslip_type_id);

            if (!$employee || !$payslipType) {
                return response()->json([
                    'success' => false,
                    'message' => __('Employee or Payslip Type not found.')
                ]);
            }

            $deductionOptions = $payslipType->deductionOptions;

            if ($deductionOptions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => __('No deduction options configured in this payslip type.')
                ]);
            }

            // Get existing deductions for this employee
            $existingDeductions = SaturationDeduction::where('employee_id', $employee->id)
                ->pluck('deduction_option')
                ->toArray();

            $appliedCount = 0;
            $appliedNames = [];

            // Apply deductions that don't already exist
            foreach ($deductionOptions as $option) {
                if (!in_array($option->id, $existingDeductions)) {
                    $deduction = new SaturationDeduction();
                    $deduction->employee_id = $employee->id;
                    $deduction->deduction_option = $option->id;
                    $deduction->title = $option->name;
                    $deduction->amount = $option->amount ?? 0;
                    $deduction->created_by = \Auth::user()->creatorId();
                    $deduction->save();
                    $appliedCount++;
                    $appliedNames[] = $option->name;
                }
            }

            if ($appliedCount > 0) {
                return response()->json([
                    'success' => true,
                    'message' => __('Successfully applied :count deductions:', ['count' => $appliedCount]) . ' ' . implode(', ', $appliedNames)
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => __('All deductions from this payslip type are already applied.')
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('An error occurred: ') . $e->getMessage()
            ]);
        }
    }

}
