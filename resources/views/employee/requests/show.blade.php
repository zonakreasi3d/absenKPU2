@extends('layouts.app')

@section('title', 'Detail Permintaan Absensi')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Detail Permintaan Absensi</h5>
                    <div>
                        <a href="{{ route('employee.requests.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                        @if($request->status === 'pending')
                            <a href="{{ route('employee.requests.edit', $request) }}" class="btn btn-warning btn-sm">Edit</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Jenis Permintaan</strong></td>
                            <td width="5%">:</td>
                            <td>
                                <span class="badge bg-{{ $request->request_type == 'remote_work' ? 'info' : 'warning' }}">
                                    {{ $request->request_type == 'remote_work' ? 'Kerja Remote' : 'Dinas Luar' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Permintaan</strong></td>
                            <td>:</td>
                            <td>{{ $request->request_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jam Mulai</strong></td>
                            <td>:</td>
                            <td>{{ $request->start_time ? $request->start_time : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jam Selesai</strong></td>
                            <td>:</td>
                            <td>{{ $request->end_time ? $request->end_time : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Lokasi</strong></td>
                            <td>:</td>
                            <td>{{ $request->location ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alasan</strong></td>
                            <td>:</td>
                            <td>{{ $request->reason }}</td>
                        </tr>
                        <tr>
                            <td><strong>Foto/Bukti</strong></td>
                            <td>:</td>
                            <td>
                                @if($request->photo)
                                    <img src="{{ asset('storage/'.$request->photo) }}" alt="Bukti" class="img-thumbnail" style="width: 200px; height: auto;">
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:</td>
                            <td>
                                <span class="badge bg-{{ $request->status == 'pending' ? 'secondary' : ($request->status == 'approved' ? 'success' : 'danger') }}">
                                    {{ $request->status == 'pending' ? 'Menunggu' : ($request->status == 'approved' ? 'Disetujui' : 'Ditolak') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Diajukan Tanggal</strong></td>
                            <td>:</td>
                            <td>{{ $request->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @if($request->approved_at)
                        <tr>
                            <td><strong>Disetujui Tanggal</strong></td>
                            <td>:</td>
                            <td>{{ $request->approved_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Disetujui Oleh</strong></td>
                            <td>:</td>
                            <td>{{ $request->approver ? $request->approver->name : '-' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection