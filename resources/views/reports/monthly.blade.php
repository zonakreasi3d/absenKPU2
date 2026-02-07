@extends('layouts.app')

@section('title', 'Laporan Bulanan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="card-title mb-0">Laporan Bulanan - {{ \Carbon\Carbon::create()->month($month)->format('F Y') }}</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('reports.export.pdf') }}?month={{ $month }}&year={{ $year }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('reports.export.excel') }}?month={{ $month }}&year={{ $year }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $attendances->count() }}</h5>
                                    <p class="card-text">Total Absensi</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $attendances->where('status', 'present')->count() }}</h5>
                                    <p class="card-text">Hadir</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $attendances->where('status', 'late')->count() }}</h5>
                                    <p class="card-text">Terlambat</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $attendances->where('status', 'early_leave')->count() }}</h5>
                                    <p class="card-text">Pulang Awal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Karyawan</th>
                                    <th>ID Karyawan</th>
                                    <th>Departemen</th>
                                    <th>Total Hari Kerja</th>
                                    <th>Hadir</th>
                                    <th>Terlambat</th>
                                    <th>Pulang Awal</th>
                                    <th>Total Jam Kerja</th>
                                    <th>Rata-rata Jam/Hari</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Group by employee
                                    $groupedAttendances = $attendances->groupBy('employee_id');
                                @endphp
                                
                                @forelse($groupedAttendances as $employeeId => $employeeAttendances)
                                    @php
                                        $employee = $employeeAttendances->first()->employee;
                                        $totalDays = $employeeAttendances->count();
                                        $presentCount = $employeeAttendances->where('status', 'present')->count();
                                        $lateCount = $employeeAttendances->where('status', 'late')->count();
                                        $earlyLeaveCount = $employeeAttendances->where('status', 'early_leave')->count();
                                        
                                        // Calculate total and average working hours
                                        $totalHours = 0;
                                        foreach($employeeAttendances as $attendance) {
                                            if($attendance->check_in_time && $attendance->check_out_time) {
                                                $hours = $attendance->check_in_time->diffInHours($attendance->check_out_time);
                                                $totalHours += $hours;
                                            }
                                        }
                                        $avgHours = $totalDays > 0 ? round($totalHours / $totalDays, 2) : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->employee_id }}</td>
                                        <td>{{ $employee->department ? $employee->department->department_name : '-' }}</td>
                                        <td>{{ $totalDays }}</td>
                                        <td>{{ $presentCount }}</td>
                                        <td>{{ $lateCount }}</td>
                                        <td>{{ $earlyLeaveCount }}</td>
                                        <td>{{ round($totalHours, 2) }} jam</td>
                                        <td>{{ $avgHours }} jam</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data absensi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection