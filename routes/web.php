<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\EmployeeController;

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

    // Employee CRUD routes
    Route::resource('employees', EmployeeController::class);
    
    // Employee import/export routes
    Route::get('employees/export', [EmployeeController::class, 'export'])->name('employees.export');
    Route::get('employees/import', [EmployeeController::class, 'showImportForm'])->name('employees.import.form');
    Route::post('employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    
    // Device CRUD routes
    Route::resource('devices', DeviceController::class);
    Route::post('devices/{device}/generate-token', [DeviceController::class, 'generateApiToken'])->name('devices.generate-token');
    
    // Routes lainnya akan ditambahkan di tahap berikutnya
    Route::get('/attendance', fn() => view('attendance.index'))->name('attendance.index');
    Route::get('/reports', fn() => view('reports.index'))->name('reports.index');
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