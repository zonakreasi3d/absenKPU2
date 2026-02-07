<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes - Admin & Super Admin
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes lainnya akan ditambahkan di tahap berikutnya
    Route::get('/employees', fn() => view('employees.index'))->name('employees.index');
    Route::get('/attendance', fn() => view('attendance.index'))->name('attendance.index');
    Route::get('/reports', fn() => view('reports.index'))->name('reports.index');
    Route::get('/devices', fn() => view('devices.index'))->name('devices.index');
    Route::get('/users', fn() => view('users.index'))->name('users.index');
    Route::get('/backup', fn() => view('backup.index'))->name('backup.index');
});

// Protected Routes - Employee
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', [EmployeeDashboardController::class, 'index'])->name('employee.dashboard');
    
    // Routes lainnya akan ditambahkan di tahap berikutnya
    Route::get('/employee/attendance', fn() => view('employee.attendance'))->name('employee.attendance');
    Route::get('/employee/requests', fn() => view('employee.requests.index'))->name('employee.requests');
    Route::get('/employee/profile', fn() => view('employee.profile'))->name('employee.profile');
});