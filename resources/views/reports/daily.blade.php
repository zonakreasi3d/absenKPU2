@extends('layouts.app')

@section('title', 'Laporan Harian')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="card-title mb-0">Laporan Harian - {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('reports.export.pdf') }}?start_date={{ $date }}&end_date={{ $date }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('reports.export.excel') }}?start_date={{ $date }}&end_date={{ $date }}" class="btn btn-success">
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
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Pulang</th>
                                    <th>Tipe Absensi</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->employee->name }}</td>
                                        <td>{{ $attendance->employee->employee_id }}</td>
                                        <td>{{ $attendance->employee->department ? $attendance->employee->department->department_name : '-' }}</td>
                                        <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}</td>
                                        <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $attendance->attendance_type == 'office' ? 'primary' : 'info' }}">
                                                {{ ucfirst($attendance->attendance_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'late' ? 'warning' : 'danger') }}">
                                                {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $attendance->notes ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data absensi</td>
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