@extends('layouts.app')

@section('title', 'Permintaan Absensi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="card-title mb-0">Permintaan Absensi Saya</h5>
                    <a href="{{ route('employee.requests.create') }}" class="btn btn-primary">Ajukan Permintaan</a>
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
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tanggal Permintaan</th>
                                    <th>Jenis Permintaan</th>
                                    <th>Lokasi</th>
                                    <th>Alasan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $request)
                                    <tr>
                                        <td>{{ $request->created_at->format('d M Y') }}</td>
                                        <td>{{ $request->request_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->request_type == 'remote_work' ? 'info' : 'warning' }}">
                                                {{ $request->request_type == 'remote_work' ? 'Kerja Remote' : 'Dinas Luar' }}
                                            </span>
                                        </td>
                                        <td>{{ $request->location ?? '-' }}</td>
                                        <td>{{ Str::limit($request->reason, 50) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->status == 'pending' ? 'secondary' : ($request->status == 'approved' ? 'success' : 'danger') }}">
                                                {{ $request->status == 'pending' ? 'Menunggu' : ($request->status == 'approved' ? 'Disetujui' : 'Ditolak') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('employee.requests.show', $request) }}" class="btn btn-info btn-sm">Lihat</a>
                                                @if($request->status === 'pending')
                                                    <a href="{{ route('employee.requests.edit', $request) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('employee.requests.destroy', $request) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus permintaan ini?')">Hapus</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada permintaan absensi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection