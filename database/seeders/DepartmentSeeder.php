<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['department_name' => 'IT', 'description' => 'Departemen Teknologi Informasi'],
            ['department_name' => 'HR', 'description' => 'Departemen Sumber Daya Manusia'],
            ['department_name' => 'Finance', 'description' => 'Departemen Keuangan'],
            ['department_name' => 'Marketing', 'description' => 'Departemen Pemasaran'],
            ['department_name' => 'Operasional', 'description' => 'Departemen Operasional'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        $this->command->info('Data departemen dummy berhasil dibuat.');
    }
}