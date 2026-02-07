@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard</h2>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Karyawan</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalEmployees }}</h2>
                        </div>
                        <div class="display-4 text-primary">
                            <i class="bi bi-people"></i>
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
                            <h6 class="text-muted mb-1">Hadir Hari Ini</h6>
                            <h2 class="mb-0 fw-bold">{{ $presentToday }}</h2>
                        </div>
                        <div class="display-4 text-success">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Terlambat Hari Ini</h6>
                            <h2 class="mb-0 fw-bold">{{ $lateToday }}</h2>
                        </div>
                        <div class="display-4 text-warning">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Kehadiran 7 Hari Terakhir</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-activity"></i> Aktivitas Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentActivity as $activity)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $activity->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($activity->check_in_time)->format('H:i') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $activity->attendance_type == 'office' ? 'primary' : 'info' }}">
                                        {{ $activity->attendance_type == 'office' ? 'Kantor' : 'Remote' }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">
                                Belum ada aktivitas
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('attendanceChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($last7Days) !!},
            datasets: [{
                label: 'Jumlah Kehadiran',
                data: {!! json_encode($attendanceData) !!},
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection