@extends('layouts.app')

@section('title', 'Detail Data Absensi')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Detail Data Absensi</h5>
                    <div>
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                        <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-warning btn-sm">Edit</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Nama Karyawan</strong></td>
                            <td width="5%">:</td>
                            <td>{{ $attendance->employee->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>ID Karyawan</strong></td>
                            <td>:</td>
                            <td>{{ $attendance->employee->employee_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Departemen</strong></td>
                            <td>:</td>
                            <td>{{ $attendance->employee->department ? $attendance->employee->department->department_name : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Perangkat</strong></td>
                            <td>:</td>
                            <td>{{ $attendance->device ? $attendance->device->device_name : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Waktu Masuk</strong></td>
                            <td>:</td>
                            <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('d M Y H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Waktu Pulang</strong></td>
                            <td>:</td>
                            <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('d M Y H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tipe Absensi</strong></td>
                            <td>:</td>
                            <td>
                                <span class="badge bg-{{ $attendance->attendance_type == 'office' ? 'primary' : 'info' }}">
                                    {{ ucfirst($attendance->attendance_type) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:</td>
                            <td>
                                <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'late' ? 'warning' : 'danger') }}">
                                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Catatan</strong></td>
                            <td>:</td>
                            <td>{{ $attendance->notes ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat Tanggal</strong></td>
                            <td>:</td>
                            <td>{{ $attendance->created_at ? $attendance->created_at->format('d M Y H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Diupdate Tanggal</strong></td>
                            <td>:</td>
                            <td>{{ $attendance->updated_at ? $attendance->updated_at->format('d M Y H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection