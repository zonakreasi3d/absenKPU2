<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Employee::with('department')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIK',
            'Nama',
            'Email',
            'Telepon',
            'Departemen',
            'Posisi',
            'Status',
        ];
    }

    /**
     * @param mixed $employee
     * @return array
     */
    public function map($employee): array
    {
        return [
            $employee->employee_id,
            $employee->name,
            $employee->email,
            $employee->phone,
            $employee->department ? $employee->department->department_name : '',
            $employee->position,
            $employee->status,
        ];
    }
}