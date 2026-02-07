@extends('layouts.app')

@section('title', 'Laporan Absensi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="card-title mb-0">Laporan Absensi</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('reports.export.pdf') }}?{{ http_build_query(request()->all()) }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('reports.export.excel') }}?{{ http_build_query(request()->all()) }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>
                
                <!-- Filter Form -->
                <div class="card-header bg-light">
                    <form method="GET" action="{{ route('reports.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Tanggal Awal</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="employee_id" class="form-label">Karyawan</label>
                            <select class="form-select" id="employee_id" name="employee_id">
                                <option value="">Semua Karyawan</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }} ({{ $employee->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="department_id" class="form-label">Departemen</label>
                            <select class="form-select" id="department_id" name="department_id">
                                <option value="">Semua Departemen</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Hadir</option>
                                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
                                <option value="early_leave" {{ request('status') == 'early_leave' ? 'selected' : '' }}>Pulang Awal</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('reports.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Ringkasan Laporan</h6>
                        <div class="btn-group" role="group">
                            <a href="{{ route('reports.daily') }}?date={{ request('start_date') ?: today()->format('Y-m-d') }}" class="btn btn-outline-primary">Harian</a>
                            <a href="{{ route('reports.weekly') }}?start_date={{ request('start_date') ?: today()->startOfWeek()->format('Y-m-d') }}&end_date={{ request('end_date') ?: today()->endOfWeek()->format('Y-m-d') }}" class="btn btn-outline-primary">Mingguan</a>
                            <a href="{{ route('reports.monthly') }}?month={{ request('month') ?: today()->format('m') }}&year={{ request('year') ?: today()->format('Y') }}" class="btn btn-outline-primary">Bulanan</a>
                            <a href="{{ route('reports.yearly') }}?year={{ request('year') ?: today()->format('Y') }}" class="btn btn-outline-primary">Tahunan</a>
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
                                        <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('d M Y H:i') : '-' }}</td>
                                        <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('d M Y H:i') : '-' }}</td>
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
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection