<?php

namespace App\Exports;

use App\Models\AttendanceRecord;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceReportExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = AttendanceRecord::with(['employee.department', 'device']);

        // Apply filters
        if (!empty($this->filters['start_date'])) {
            $query->whereDate('check_in_time', '>=', $this->filters['start_date']);
        }
        
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('check_in_time', '<=', $this->filters['end_date']);
        }

        if (!empty($this->filters['employee_id'])) {
            $query->where('employee_id', $this->filters['employee_id']);
        }

        if (!empty($this->filters['department_id'])) {
            $query->whereHas('employee', function ($q) {
                $q->where('department_id', $this->filters['department_id']);
            });
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        $attendances = $query->get();

        return view('exports.attendance-report', [
            'attendances' => $attendances
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}