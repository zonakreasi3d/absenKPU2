<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
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
        $devices = Device::all();

        return view('attendance.index', compact('attendances', 'employees', 'devices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::with('department')->get();
        $devices = Device::all();
        return view('attendance.create', compact('employees', 'devices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'device_id' => 'nullable|exists:devices,id',
            'check_in_time' => 'required|date',
            'check_out_time' => 'nullable|date|after_or_equal:check_in_time',
            'attendance_type' => 'required|in:office,remote',
            'status' => 'required|in:present,late,early_leave',
            'notes' => 'nullable|string|max:500',
        ]);

        AttendanceRecord::create($request->all());

        return redirect()->route('attendance.index')->with('success', 'Data absensi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendanceRecord $attendance)
    {
        $attendance->load(['employee.department', 'device']);
        return view('attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendanceRecord $attendance)
    {
        $attendance->load(['employee.department', 'device']);
        $employees = Employee::with('department')->get();
        $devices = Device::all();
        return view('attendance.edit', compact('attendance', 'employees', 'devices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttendanceRecord $attendance)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'device_id' => 'nullable|exists:devices,id',
            'check_in_time' => 'required|date',
            'check_out_time' => 'nullable|date|after_or_equal:check_in_time',
            'attendance_type' => 'required|in:office,remote',
            'status' => 'required|in:present,late,early_leave',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendance->update($request->all());

        return redirect()->route('attendance.index')->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceRecord $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendance.index')->with('success', 'Data absensi berhasil dihapus.');
    }
}
