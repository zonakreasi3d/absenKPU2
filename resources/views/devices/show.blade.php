@extends('layouts.app')

@section('title', 'Detail Mesin Absensi')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Detail Mesin Absensi</h5>
                    <div>
                        <a href="{{ route('devices.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                        <a href="{{ route('devices.edit', $device) }}" class="btn btn-warning btn-sm">Edit</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Nama Mesin</strong></td>
                            <td width="5%">:</td>
                            <td>{{ $device->device_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Lokasi</strong></td>
                            <td>:</td>
                            <td>{{ $device->device_location }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tipe Mesin</strong></td>
                            <td>:</td>
                            <td>
                                <span class="badge bg-{{ $device->device_type == 'handkey' ? 'primary' : 'info' }}">
                                    {{ ucfirst($device->device_type) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Nomor Seri</strong></td>
                            <td>:</td>
                            <td>{{ $device->serial_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alamat IP</strong></td>
                            <td>:</td>
                            <td>{{ $device->ip_address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:</td>
                            <td>
                                <span class="badge bg-{{ $device->status == 'active' ? 'success' : ($device->status == 'inactive' ? 'secondary' : 'warning') }}">
                                    {{ ucfirst($device->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>API Token</strong></td>
                            <td>:</td>
                            <td>
                                @if($device->api_token)
                                    <code>{{ $device->api_token }}</code>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('{{ $device->api_token }}')" title="Salin ke clipboard">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                @else
                                    <em>Belum digenerate</em>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat Tanggal</strong></td>
                            <td>:</td>
                            <td>{{ $device->created_at ? $device->created_at->format('d M Y H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Diupdate Tanggal</strong></td>
                            <td>:</td>
                            <td>{{ $device->updated_at ? $device->updated_at->format('d M Y H:i') : '-' }}</td>
                        </tr>
                    </table>
                    
                    <div class="mt-4">
                        <a href="{{ route('devices.generate-token', $device) }}" class="btn btn-secondary" onclick="return confirm('Apakah Anda yakin ingin generate API token? Token lama akan digantikan.')">
                            Generate API Token
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('API Token telah disalin ke clipboard!');
    }, function(err) {
        console.error('Gagal menyalin teks: ', err);
    });
}
</script>
@endsection