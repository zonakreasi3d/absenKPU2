<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\Employee\AttendanceController as EmployeeAttendanceController;
use App\Http\Controllers\Employee\AttendanceRequestController as EmployeeAttendanceRequestController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\ProfileController as EmployeeProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;


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
    
    // Attendance CRUD routes
    Route::resource('attendance', AttendanceController::class);
    
    // Report routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/daily', [ReportController::class, 'dailyReport'])->name('daily');
        Route::get('/weekly', [ReportController::class, 'weeklyReport'])->name('weekly');
        Route::get('/monthly', [ReportController::class, 'monthlyReport'])->name('monthly');
        Route::get('/yearly', [ReportController::class, 'yearlyReport'])->name('yearly');
        Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
    });
    
    // User management routes
    Route::resource('users', UserController::class);
    Route::get('/users/{user}/reset-password', [UserController::class, 'showResetPasswordForm'])->name('users.reset.password.form');
    Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset.password');
    
    // Backup routes
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::get('/backup/create', [BackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{filename}', [BackupController::class, 'destroy'])->name('backup.destroy');
});

// Protected Routes - Employee
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', [EmployeeDashboardController::class, 'index'])->name('employee.dashboard');

    // Employee features routes
    Route::resource('employee/attendance', EmployeeAttendanceController::class);
    Route::resource('employee/requests', EmployeeAttendanceRequestController::class);
    Route::get('employee/profile', [EmployeeProfileController::class, 'show'])->name('employee.profile.show');
    Route::get('employee/profile/edit', [EmployeeProfileController::class, 'edit'])->name('employee.profile.edit');
    Route::put('employee/profile', [EmployeeProfileController::class, 'update'])->name('employee.profile.update');
});