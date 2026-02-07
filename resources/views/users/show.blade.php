@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Detail User</h5>
                    <div>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Nama</strong></td>
                            <td width="5%">:</td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>:</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Role</strong></td>
                            <td>:</td>
                            <td>
                                <span class="badge bg-{{ $user->role == 'super_admin' ? 'danger' : 'warning' }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>:</td>
                            <td>
                                <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat Tanggal</strong></td>
                            <td>:</td>
                            <td>{{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Diupdate Tanggal</strong></td>
                            <td>:</td>
                            <td>{{ $user->updated_at ? $user->updated_at->format('d M Y H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection