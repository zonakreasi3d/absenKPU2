<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // HAPUS constructor dengan middleware
    // Constructor tidak perlu lagi untuk middleware di Laravel 12

    public function index()
    {
        $user = auth()->user();

        // Periksa apakah tabel attendance_records ada
        $hasAttendanceTable = DB::getSchemaBuilder()->hasTable('attendance_records');
        
        // Inisialisasi variabel default
        $monthlyAttendance = 0;
        $totalAttendance = 0;
        $lastAttendance = null;
        $calendarData = [];

        if ($hasAttendanceTable) {
            // Absen bulan ini
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            $monthlyAttendance = DB::table('attendance_records')
                ->where('employee_id', $user->id)
                ->whereMonth('check_in_time', $currentMonth)
                ->whereYear('check_in_time', $currentYear)
                ->count();

            // Total hadir
            $totalAttendance = DB::table('attendance_records')
                ->where('employee_id', $user->id)
                ->count();

            // Absen terakhir
            $lastAttendance = DB::table('attendance_records')
                ->where('employee_id', $user->id)
                ->orderBy('check_in_time', 'desc')
                ->first();

            // Jadwal absen 7 hari ke depan (kalender)
            for ($i = 0; $i < 7; $i++) {
                $date = Carbon::today()->addDays($i);
                $hasAttendance = DB::table('attendance_records')
                    ->where('employee_id', $user->id)
                    ->whereDate('check_in_time', $date)
                    ->exists();

                $calendarData[] = [
                    'date' => $date->format('Y-m-d'),
                    'day' => $date->format('D'),
                    'day_name' => $date->locale('id')->dayName,
                    'has_attendance' => $hasAttendance
                ];
            }
        } else {
            // Jika tabel attendance_records belum ada, buat data kosong
            for ($i = 0; $i < 7; $i++) {
                $date = Carbon::today()->addDays($i);
                
                $calendarData[] = [
                    'date' => $date->format('Y-m-d'),
                    'day' => $date->format('D'),
                    'day_name' => $date->locale('id')->dayName,
                    'has_attendance' => false
                ];
            }
        }

        return view('employee.dashboard', compact(
            'monthlyAttendance',
            'totalAttendance',
            'lastAttendance',
            'calendarData'
        ));
    }
}