@extends('layouts.app')

@section('title', 'Manajemen Mesin Absensi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="card-title mb-0">Daftar Mesin Absensi</h5>
                    <a href="{{ route('devices.create') }}" class="btn btn-primary">Tambah Mesin</a>
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
                                    <th>Nama Mesin</th>
                                    <th>Lokasi</th>
                                    <th>Tipe</th>
                                    <th>Nomor Seri</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($devices as $device)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $device->device_name }}</td>
                                        <td>{{ $device->device_location }}</td>
                                        <td>
                                            <span class="badge bg-{{ $device->device_type == 'handkey' ? 'primary' : 'info' }}">
                                                {{ ucfirst($device->device_type) }}
                                            </span>
                                        </td>
                                        <td>{{ $device->serial_number }}</td>
                                        <td>{{ $device->ip_address ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $device->status == 'active' ? 'success' : ($device->status == 'inactive' ? 'secondary' : 'warning') }}">
                                                {{ ucfirst($device->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('devices.show', $device) }}" class="btn btn-info btn-sm">Lihat</a>
                                                <a href="{{ route('devices.edit', $device) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('devices.destroy', $device) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus mesin ini?')">Hapus</button>
                                                </form>
                                                <a href="{{ route('devices.generate-token', $device) }}" class="btn btn-secondary btn-sm" onclick="return confirm('Apakah Anda yakin ingin generate ulang API token?')">Generate Token</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data mesin absensi</td>
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