@extends('layouts.app')

@section('title', 'Backup Database')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="card-title mb-0">Backup Database</h5>
                    <a href="{{ route('backup.create') }}" class="btn btn-primary">Buat Backup Baru</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(count($backups) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama File</th>
                                        <th>Ukuran</th>
                                        <th>Terakhir Dimodifikasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td>{{ $backup['filename'] }}</td>
                                            <td>{{ number_format($backup['size'] / 1024 / 1024, 2) }} MB</td>
                                            <td>{{ date('d M Y H:i:s', $backup['modified']) }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('backup.download', $backup['filename']) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                    <form action="{{ route('backup.destroy', $backup['filename']) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus backup ini?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-database fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada backup</h5>
                            <p class="text-muted">Buat backup database pertama Anda sekarang</p>
                            <a href="{{ route('backup.create') }}" class="btn btn-primary">Buat Backup Sekarang</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection