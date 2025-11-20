<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsErrors;

    protected $errors = [];
    protected $rowNumber = 1;
    protected $created = 0;
    protected $updated = 0;
    protected $skipped = 0;

    public function model(array $row)
    {
        $this->rowNumber++;

        // Skip empty rows
        if (empty($row['first_name']) && empty($row['last_name']) && empty($row['full_name']) && empty($row['email_id'])) {
            return null;
        }

        // Find branch by name (case-insensitive)
        $branch = null;
        if (!empty($row['branch'])) {
            $branch = Branch::whereRaw('LOWER(name) = ?', [strtolower($row['branch'])])
                ->where('created_by', \Auth::user()->creatorId())
                ->first();

            if (!$branch) {
                $this->errors[] = "Row {$this->rowNumber}: Branch '{$row['branch']}' not found. Row skipped.";
                $this->skipped++;
                return null;
            }
        }

        // Find department by name (case-insensitive)
        $department = null;
        if (!empty($row['department'])) {
            $department = Department::whereRaw('LOWER(name) = ?', [strtolower($row['department'])])
                ->where('created_by', \Auth::user()->creatorId())
                ->first();

            if (!$department) {
                $this->errors[] = "Row {$this->rowNumber}: Department '{$row['department']}' not found. Row skipped.";
                $this->skipped++;
                return null;
            }
        }

        // Find designation by name (case-insensitive)
        $designation = null;
        if (!empty($row['designation'])) {
            $designation = Designation::whereRaw('LOWER(name) = ?', [strtolower($row['designation'])])
                ->where('created_by', \Auth::user()->creatorId())
                ->first();

            if (!$designation) {
                $this->errors[] = "Row {$this->rowNumber}: Designation '{$row['designation']}' not found. Row skipped.";
                $this->skipped++;
                return null;
            }
        }

        // Build full name from parts if not provided
        $fullName = $row['full_name'] ?? '';
        $firstName = $row['first_name'] ?? '';
        $middleName = $row['middle_name'] ?? '';
        $lastName = $row['last_name'] ?? '';

        // If full name not provided but parts are, construct it
        if (empty($fullName) && (!empty($firstName) || !empty($lastName))) {
            $nameParts = array_filter([$firstName, $middleName, $lastName]);
            $fullName = implode(' ', $nameParts);
        }

        // Find manager/reports_to by employee_id
        $reportsTo = null;
        if (!empty($row['reports_to'])) {
            $manager = Employee::where('employee_id', $row['reports_to'])
                ->where('created_by', \Auth::user()->creatorId())
                ->first();
            if ($manager) {
                $reportsTo = $manager->id;
            }
        }

        $employeeData = [
            'name' => $fullName,
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'ssn' => $row['ssn'] ?? '',
            'man_number' => $row['man_number'] ?? '',
            'dob' => $row['date_of_birth'] ?? null,
            'gender' => $row['gender'] ?? '',
            'phone' => $row['phone_number'] ?? '',
            'address' => $row['address'] ?? '',
            'email' => $row['email_id'] ?? '',
            'branch_id' => $branch ? $branch->id : null,
            'department_id' => $department ? $department->id : null,
            'designation_id' => $designation ? $designation->id : null,
            'company_doj' => $row['date_of_join'] ?? null,
            'reports_to' => $reportsTo,
            'account_holder_name' => $row['account_holder_name'] ?? '',
            'account_number' => $row['account_number'] ?? '',
            'bank_name' => $row['bank_name'] ?? '',
            'bank_identifier_code' => $row['bank_identifier_code'] ?? '',
            'branch_location' => $row['branch_location'] ?? '',
            'tax_payer_id' => $row['tax_payer_id'] ?? '',
            'salary' => $row['salary'] ?? 0,
            'nrc_number' => $row['nrc_number'] ?? '',
            'drivers_license_number' => $row['drivers_license_number'] ?? '',
            'passport_number' => $row['passport_number'] ?? '',
            'created_by' => \Auth::user()->creatorId(),
        ];

        // Check if employee exists by email
        if (!empty($row['email_id'])) {
            $existingEmployee = Employee::where('email', $row['email_id'])
                ->where('created_by', \Auth::user()->creatorId())
                ->first();

            if ($existingEmployee) {
                // Update existing employee
                $existingEmployee->update($employeeData);
                $this->updated++;
                return null;
            }
        }

        // Create new employee
        $this->created++;
        return new Employee($employeeData);
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'nullable|string|max:191',
            'middle_name' => 'nullable|string|max:191',
            'last_name' => 'nullable|string|max:191',
            'full_name' => 'nullable|string|max:191',
            'email_id' => 'nullable|email|max:191',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'email_id.email' => 'Email must be a valid email address.',
            'first_name.string' => 'First name must be text.',
            'middle_name.string' => 'Middle name must be text.',
            'last_name.string' => 'Last name must be text.',
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getSkipped()
    {
        return $this->skipped;
    }
}
