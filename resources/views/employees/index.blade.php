@extends('layouts.app')

@section('title', 'Manajemen Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="card-title mb-0">Daftar Karyawan</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">Tambah Karyawan</a>
                        <a href="{{ route('employees.import.form') }}" class="btn btn-info">Import Excel</a>
                        <a href="{{ route('employees.export') }}" class="btn btn-success">Export Excel</a>
                    </div>
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
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Departemen</th>
                                    <th>Posisi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    <tr>
                                        <td>{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                                        <td>{{ $employee->employee_id }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>{{ $employee->phone ?? '-' }}</td>
                                        <td>{{ $employee->department ? $employee->department->department_name : '-' }}</td>
                                        <td>{{ $employee->position ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $employee->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($employee->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('employees.show', $employee) }}" class="btn btn-info btn-sm">Lihat</a>
                                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus karyawan ini?')">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data karyawan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection