@extends('layouts.app')

@section('title', 'Import Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Import Data Karyawan dari Excel</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Petunjuk Import:</h6>
                        <ul>
                            <li>Gunakan format file .xlsx, .xls, atau .csv</li>
                            <li>Kolom yang wajib diisi: <strong>NIK</strong>, <strong>Nama</strong>, <strong>Email</strong></li>
                            <li>Nama kolom yang didukung: nik/employee_id, nama/name, email, telepon/phone, department_name, posisi/position, status</li>
                            <li>Download <a href="{{ asset('templates/template_import_karyawan.xlsx') }}">template import</a> untuk format yang benar</li>
                        </ul>
                    </div>
                    
                    <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="file" class="form-label">File Excel <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection