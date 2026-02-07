<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Department;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan department ada dulu
        $itDept = Department::firstOrCreate([
            'department_name' => 'IT'
        ], [
            'department_name' => 'IT',
            'description' => 'Departemen Teknologi Informasi'
        ]);
        
        $hrDept = Department::firstOrCreate([
            'department_name' => 'HR'
        ], [
            'department_name' => 'HR',
            'description' => 'Departemen Human Resources'
        ]);

        // Create sample employees
        Employee::create([
            'employee_id' => 'EMP001',
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@example.com',
            'phone' => '081234567890',
            'department_id' => $itDept->id,
            'position' => 'Software Engineer',
            'status' => 'active',
        ]);

        Employee::create([
            'employee_id' => 'EMP002',
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nur@example.com',
            'phone' => '081234567891',
            'department_id' => $hrDept->id,
            'position' => 'HR Specialist',
            'status' => 'active',
        ]);

        Employee::create([
            'employee_id' => 'EMP003',
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'phone' => '081234567892',
            'department_id' => $itDept->id,
            'position' => 'System Administrator',
            'status' => 'active',
        ]);
    }
}
