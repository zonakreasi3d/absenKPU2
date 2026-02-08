<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Device;
use App\Models\AttendanceRecord;
use App\Models\AttendanceLog;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua employee dan device
        $employees = Employee::all();
        $devices = Device::all();

        if ($employees->isEmpty() || $devices->isEmpty()) {
            $this->command->info('Belum ada data employee atau device, mohon jalankan seeder terlebih dahulu.');
            return;
        }

        // Ambil employee yang terkait dengan user employee
        $employeeWithUser = Employee::whereHas('user', function($query) {
            $query->where('role', 'employee');
        })->first();

        if (!$employeeWithUser) {
            // Jika tidak ada employee yang terkait dengan user employee, ambil employee pertama
            $employeeWithUser = $employees->first();
        }

        $device = $devices->first();

        // Buat data absensi dummy untuk beberapa hari terakhir
        $dates = [
            Carbon::now()->subDays(0)->format('Y-m-d'), // Hari ini
            Carbon::now()->subDays(1)->format('Y-m-d'), // Kemarin
            Carbon::now()->subDays(2)->format('Y-m-d'),
            Carbon::now()->subDays(3)->format('Y-m-d'),
            Carbon::now()->subDays(4)->format('Y-m-d'),
            Carbon::now()->subDays(5)->format('Y-m-d'),
            Carbon::now()->subDays(6)->format('Y-m-d'),
        ];

        foreach ($dates as $date) {
            // Jam masuk antara 07:00-09:00
            $checkInHour = rand(7, 9);
            $checkInMinute = rand(0, 59);
            $checkInSecond = rand(0, 59);
            $checkInTime = Carbon::createFromFormat('Y-m-d H:i:s', "$date ".sprintf('%02d', $checkInHour).":".sprintf('%02d', $checkInMinute).":".sprintf('%02d', $checkInSecond));

            // Tentukan status keterlambatan
            $status = $checkInHour > 8 ? 'late' : 'present';

            // Jam pulang antara 16:00-18:00
            $checkOutHour = rand(16, 18);
            $checkOutMinute = rand(0, 59);
            $checkOutSecond = rand(0, 59);
            $checkOutTime = Carbon::createFromFormat('Y-m-d H:i:s', "$date ".sprintf('%02d', $checkOutHour).":".sprintf('%02d', $checkOutMinute).":".sprintf('%02d', $checkOutSecond));

            // Buat record absensi
            $attendanceRecord = AttendanceRecord::create([
                'employee_id' => $employeeWithUser->id,
                'device_id' => $device->id,
                'check_in_time' => $checkInTime,
                'check_out_time' => $checkOutTime,
                'check_in_location' => 'Kantor Pusat',
                'check_out_location' => 'Kantor Pusat',
                'attendance_type' => 'office',
                'status' => $status,
                'notes' => 'Absensi harian',
            ]);

            // Buat log absensi untuk check-in
            AttendanceLog::create([
                'device_id' => $device->id,
                'raw_data' => "{$employeeWithUser->employee_id}|{$checkInTime}|in",
                'employee_id' => $employeeWithUser->id,
                'timestamp' => $checkInTime,
                'log_type' => 'in',
                'processed' => true,
            ]);

            // Buat log absensi untuk check-out
            AttendanceLog::create([
                'device_id' => $device->id,
                'raw_data' => "{$employeeWithUser->employee_id}|{$checkOutTime}|out",
                'employee_id' => $employeeWithUser->id,
                'timestamp' => $checkOutTime,
                'log_type' => 'out',
                'processed' => true,
            ]);
        }

        // Tambahkan beberapa data untuk employee lainnya
        foreach ($employees->skip(1) as $idx => $emp) {
            if ($idx >= 2) break; // Batasi hanya 2 employee tambahan

            $checkInHour = rand(7, 9);
            $checkInMinute = rand(0, 59);
            $checkInSecond = rand(0, 59);
            $checkInTime = Carbon::now()->subDays(1)->setTime($checkInHour, $checkInMinute, $checkInSecond);
            
            $checkOutHour = rand(16, 18);
            $checkOutMinute = rand(0, 59);
            $checkOutSecond = rand(0, 59);
            $checkOutTime = Carbon::now()->subDays(1)->setTime($checkOutHour, $checkOutMinute, $checkOutSecond);

            $status = $checkInTime->hour > 8 ? 'late' : 'present';

            AttendanceRecord::create([
                'employee_id' => $emp->id,
                'device_id' => $device->id,
                'check_in_time' => $checkInTime,
                'check_out_time' => $checkOutTime,
                'check_in_location' => 'Kantor Pusat',
                'check_out_location' => 'Kantor Pusat',
                'attendance_type' => 'office',
                'status' => $status,
                'notes' => 'Absensi harian',
            ]);

            // Buat log absensi
            AttendanceLog::create([
                'device_id' => $device->id,
                'raw_data' => "{$emp->employee_id}|{$checkInTime}|in",
                'employee_id' => $emp->id,
                'timestamp' => $checkInTime,
                'log_type' => 'in',
                'processed' => true,
            ]);

            AttendanceLog::create([
                'device_id' => $device->id,
                'raw_data' => "{$emp->employee_id}|{$checkOutTime}|out",
                'employee_id' => $emp->id,
                'timestamp' => $checkOutTime,
                'log_type' => 'out',
                'processed' => true,
            ]);
        }

        $this->command->info('Data absensi dummy berhasil dibuat.');
    }
}