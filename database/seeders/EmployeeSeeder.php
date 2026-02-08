<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();
        $users = User::where('role', 'employee')->get();
        
        if ($departments->isEmpty()) {
            $this->command->info('Belum ada data departemen, mohon jalankan DepartmentSeeder terlebih dahulu.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->info('Belum ada user dengan role employee, mohon jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $employees = [
            [
                'user_id' => $users->first()->id, // Hubungkan dengan user employee
                'employee_id' => 'EMP001',
                'name' => 'Ahmad Kurniawan',
                'email' => 'ahmad.kurniawan@example.com',
                'phone' => '081234567890',
                'department_id' => $departments->first()->id,
                'position' => 'Staff IT',
                'status' => 'active',
            ],
            [
                'user_id' => null, // Untuk karyawan lainnya, bisa kita kosongkan dulu
                'employee_id' => 'EMP002',
                'name' => 'Siti Rahayu',
                'email' => 'siti.rahayu@example.com',
                'phone' => '081234567891',
                'department_id' => $departments->skip(1)->first()->id,
                'position' => 'Staff HR',
                'status' => 'active',
            ],
            [
                'user_id' => null,
                'employee_id' => 'EMP003',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone' => '081234567892',
                'department_id' => $departments->skip(2)->first()->id,
                'position' => 'Staff Finance',
                'status' => 'active',
            ],
            [
                'user_id' => null,
                'employee_id' => 'EMP004',
                'name' => 'Dewi Anggraini',
                'email' => 'dewi.anggraini@example.com',
                'phone' => '081234567893',
                'department_id' => $departments->skip(3)->first()->id,
                'position' => 'Staff Marketing',
                'status' => 'active',
            ],
            [
                'user_id' => null,
                'employee_id' => 'EMP005',
                'name' => 'Rizki Pratama',
                'email' => 'rizki.pratama@example.com',
                'phone' => '081234567894',
                'department_id' => $departments->last()->id,
                'position' => 'Staff Operasional',
                'status' => 'active',
            ],
        ];

        foreach ($employees as $emp) {
            Employee::create($emp);
        }

        $this->command->info('Data karyawan dummy berhasil dibuat.');
    }
}