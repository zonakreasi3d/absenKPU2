@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Detail Karyawan</h5>
                    <div>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm">Edit</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            @if($employee->photo)
                                <img src="{{ asset('storage/'.$employee->photo) }}" alt="Foto Karyawan" class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 200px; height: 200px; border-radius: 50%;">
                                    <span class="text-muted">Tidak ada foto</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>NIK</strong></td>
                                    <td width="5%">:</td>
                                    <td>{{ $employee->employee_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama</strong></td>
                                    <td>:</td>
                                    <td>{{ $employee->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>:</td>
                                    <td>{{ $employee->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telepon</strong></td>
                                    <td>:</td>
                                    <td>{{ $employee->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Departemen</strong></td>
                                    <td>:</td>
                                    <td>{{ $employee->department ? $employee->department->department_name : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Posisi</strong></td>
                                    <td>:</td>
                                    <td>{{ $employee->position ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>:</td>
                                    <td>
                                        <span class="badge bg-{{ $employee->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($employee->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat Tanggal</strong></td>
                                    <td>:</td>
                                    <td>{{ $employee->created_at ? $employee->created_at->format('d M Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diupdate Tanggal</strong></td>
                                    <td>:</td>
                                    <td>{{ $employee->updated_at ? $employee->updated_at->format('d M Y H:i') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection