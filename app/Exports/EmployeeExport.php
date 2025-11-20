<?php

namespace App\Exports;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Employee::with(['branch', 'department', 'designation', 'manager'])
            ->get()
            ->map(function (Employee $employee) {
                $branch = $employee->branch;
                $department = $employee->department;
                $designation = $employee->designation;
                $manager = $employee->manager;

                return [
                    'full_name' => $employee->name ?? '-',
                    'first_name' => $employee->first_name ?? '-',
                    'middle_name' => $employee->middle_name ?? '-',
                    'last_name' => $employee->last_name ?? '-',
                    'ssn' => $employee->ssn ?? '-',
                    'man_number' => $employee->man_number ?? '-',
                    'date_of_birth' => $employee->dob ?? '-',
                    'gender' => $employee->gender ?? '-',
                    'phone_number' => $employee->phone ?? '-',
                    'address' => $employee->address ?? '-',
                    'email_id' => $employee->email ?? '-',
                    'branch' => $branch ? $branch->name : '-',
                    'department' => $department ? $department->name : '-',
                    'designation' => $designation ? $designation->name : '-',
                    'date_of_join' => $employee->company_doj ?? '-',
                    'reports_to' => $manager ? $manager->employee_id : '-',
                    'account_holder_name' => $employee->account_holder_name ?? '-',
                    'account_number' => $employee->account_number ?? '-',
                    'bank_name' => $employee->bank_name ?? '-',
                    'bank_identifier_code' => $employee->bank_identifier_code ?? '-',
                    'branch_location' => $employee->branch_location ?? '-',
                    'tax_payer_id' => $employee->tax_payer_id ?? '-',
                    'salary' => !empty($employee->salary) ? Employee::employee_salary($employee->salary) : '-',
                    'nrc_number' => $employee->nrc_number ?? '-',
                    'drivers_license_number' => $employee->drivers_license_number ?? '-',
                    'passport_number' => $employee->passport_number ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            "Full Name",
            "First Name",
            "Middle Name",
            "Last Name",
            "SSN",
            "MAN Number",
            "Date of Birth",
            "Gender",
            "Phone Number",
            "Address",
            "Email ID",
            "Branch",
            "Department",
            "Designation",
            "Date of Join",
            "Reports To",
            "Account Holder Name",
            "Account Number",
            "Bank Name",
            "Bank Identifier Code",
            "Branch Location",
            "Tax Payer ID",
            "Salary",
            "NRC Number",
            "Driver's License Number",
            "Passport Number",
        ];
    }
}
