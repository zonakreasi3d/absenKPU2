<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Cari department berdasarkan nama
        $department = Department::where('department_name', $row['department_name'] ?? null)->first();
        
        return new Employee([
            'employee_id' => $row['nik'] ?? $row['employee_id'],
            'name' => $row['nama'] ?? $row['name'],
            'email' => $row['email'],
            'phone' => $row['telepon'] ?? $row['phone'] ?? null,
            'department_id' => $department ? $department->id : null,
            'position' => $row['posisi'] ?? $row['position'] ?? null,
            'status' => $row['status'] ?? 'active', // Default ke active
        ]);
    }

    /**
     * Rules untuk validasi
     */
    public function rules(): array
    {
        return [
            'nik' => 'required|unique:employees,employee_id',
            'nama' => 'required',
            'email' => 'required|email|unique:employees,email',
        ];
    }
}