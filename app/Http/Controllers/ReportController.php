<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceReportExport;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AttendanceRecord::with(['employee.department', 'device']);

        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('check_in_time', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('check_in_time', '<=', $request->end_date);
        }

        // Filter berdasarkan karyawan
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter berdasarkan departemen
        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Filter berdasarkan status kehadiran
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest()->paginate(10);
        
        // Ambil data untuk filter
        $employees = Employee::with('department')->get();
        $departments = Department::all();

        return view('reports.index', compact('attendances', 'employees', 'departments'));
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = AttendanceRecord::with(['employee.department', 'device']);

        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('check_in_time', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('check_in_time', '<=', $request->end_date);
        }

        // Filter berdasarkan karyawan
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter berdasarkan departemen
        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Filter berdasarkan status kehadiran
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->get();
        
        $pdf = Pdf::loadView('reports.pdf', compact('attendances'));
        return $pdf->download('laporan_absensi_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $filters = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'employee_id' => $request->employee_id,
            'department_id' => $request->department_id,
            'status' => $request->status,
        ];

        return Excel::download(new AttendanceReportExport($filters), 'laporan_absensi_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Generate daily report
     */
    public function dailyReport(Request $request)
    {
        $date = $request->date ?? today()->format('Y-m-d');
        
        $attendances = AttendanceRecord::with(['employee.department', 'device'])
            ->whereDate('check_in_time', $date)
            ->get();

        return view('reports.daily', compact('attendances', 'date'));
    }

    /**
     * Generate weekly report
     */
    public function weeklyReport(Request $request)
    {
        $startDate = $request->start_date ?? today()->startOfWeek()->format('Y-m-d');
        $endDate = $request->end_date ?? today()->endOfWeek()->format('Y-m-d');
        
        $attendances = AttendanceRecord::with(['employee.department', 'device'])
            ->whereBetween(DB::raw('DATE(check_in_time)'), [$startDate, $endDate])
            ->get();

        return view('reports.weekly', compact('attendances', 'startDate', 'endDate'));
    }

    /**
     * Generate monthly report
     */
    public function monthlyReport(Request $request)
    {
        $month = $request->month ?? today()->format('m');
        $year = $request->year ?? today()->format('Y');
        
        $attendances = AttendanceRecord::with(['employee.department', 'device'])
            ->whereMonth('check_in_time', $month)
            ->whereYear('check_in_time', $year)
            ->get();

        return view('reports.monthly', compact('attendances', 'month', 'year'));
    }

    /**
     * Generate yearly report
     */
    public function yearlyReport(Request $request)
    {
        $year = $request->year ?? today()->format('Y');
        
        $attendances = AttendanceRecord::with(['employee.department', 'device'])
            ->whereYear('check_in_time', $year)
            ->get();

        return view('reports.yearly', compact('attendances', 'year'));
    }
}
