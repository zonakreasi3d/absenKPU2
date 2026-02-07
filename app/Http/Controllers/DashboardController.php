<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // HAPUS constructor dengan middleware
    // Constructor tidak perlu lagi untuk middleware di Laravel 12

    public function index()
    {
        // Total karyawan
        $totalEmployees = User::where('role', 'employee')->count();

        // Periksa apakah tabel attendance_records ada
        $hasAttendanceTable = DB::getSchemaBuilder()->hasTable('attendance_records');
        
        // Inisialisasi variabel default
        $presentToday = 0;
        $lateToday = 0;
        $recentActivity = collect([]);
        $attendanceData = [];
        $last7Days = [];

        if ($hasAttendanceTable) {
            // Karyawan yang hadir hari ini
            $today = Carbon::today();
            $presentToday = DB::table('attendance_records')
                ->whereDate('check_in_time', $today)
                ->distinct('employee_id')
                ->count();

            // Karyawan terlambat hari ini (> 09:00)
            $lateToday = DB::table('attendance_records')
                ->whereDate('check_in_time', $today)
                ->whereTime('check_in_time', '>', '09:00:00')
                ->count();

            // Absen terbaru (5 data)
            $recentActivity = DB::table('attendance_records')
                ->join('users', 'attendance_records.employee_id', '=', 'users.id')
                ->select(
                    'users.name',
                    'attendance_records.check_in_time',
                    'attendance_records.check_out_time',
                    'attendance_records.attendance_type'
                )
                ->orderBy('attendance_records.check_in_time', 'desc')
                ->limit(5)
                ->get();

            // Data untuk chart (7 hari terakhir)
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $last7Days[] = $date->format('d M');

                $count = DB::table('attendance_records')
                    ->whereDate('check_in_time', $date)
                    ->distinct('employee_id')
                    ->count();

                $attendanceData[] = $count;
            }
        } else {
            // Jika tabel attendance_records belum ada, buat array kosong
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $last7Days[] = $date->format('d M');
                $attendanceData[] = 0;
            }
        }

        return view('dashboard.index', compact(
            'totalEmployees',
            'presentToday',
            'lateToday',
            'recentActivity',
            'last7Days',
            'attendanceData'
        ));
    }
}