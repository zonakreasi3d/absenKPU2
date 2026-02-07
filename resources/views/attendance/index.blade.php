@extends('layouts.app')

@section('title', 'Manajemen Data Absensi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="card-title mb-0">Data Absensi</h5>
                    <a href="{{ route('attendance.create') }}" class="btn btn-primary">Tambah Absensi</a>
                </div>
                
                <!-- Filter Form -->
                <div class="card-header bg-light">
                    <form method="GET" action="{{ route('attendance.index') }}" class="row g-3">
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
                            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>ID Karyawan</th>
                                    <th>Departemen</th>
                                    <th>Waktu Masuk</th>
                                    <th>Waktu Pulang</th>
                                    <th>Tipe Absensi</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                    <tr>
                                        <td>{{ ($attendances->currentPage() - 1) * $attendances->perPage() + $loop->iteration }}</td>
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
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('attendance.show', $attendance) }}" class="btn btn-info btn-sm">Lihat</a>
                                                <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?')">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data absensi</td>
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