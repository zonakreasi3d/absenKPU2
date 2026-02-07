<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'device_id',
        'check_in_time',
        'check_out_time',
        'check_in_location',
        'check_out_location',
        'attendance_type',
        'status',
        'notes',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    // Relasi ke karyawan
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // Relasi ke device
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}
