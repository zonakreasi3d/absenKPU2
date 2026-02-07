@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard Saya</h2>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Absen Bulan Ini</h6>
                            <h2 class="mb-0 fw-bold">{{ $monthlyAttendance }}</h2>
                        </div>
                        <div class="display-4 text-primary">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Hadir</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalAttendance }}</h2>
                        </div>
                        <div class="display-4 text-success">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Absen Terakhir</h6>
                            @if($lastAttendance)
                                <h6 class="mb-0">{{ \Carbon\Carbon::parse($lastAttendance->check_in_time)->format('d M Y, H:i') }}</h6>
                            @else
                                <h6 class="mb-0 text-muted">Belum ada</h6>
                            @endif
                        </div>
                        <div class="display-4 text-info">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Kalender Absensi</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        @foreach($calendarData as $day)
                            <div class="col-2 mb-3">
                                <div class="p-2 rounded {{ $day['has_attendance'] ? 'bg-success text-white' : 'bg-light' }}">
                                    <div class="fw-bold">{{ $day['day'] }}</div>
                                    <div>{{ $day['day_name'] }}</div>
                                    <small class="mt-1 d-block">
                                        {{ \Carbon\Carbon::parse($day['date'])->format('d/m') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-circle"></i> Profil Saya</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                    <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Role: <strong>{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</strong>
                    </div>
                    <a href="{{ route('employee.profile') }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection