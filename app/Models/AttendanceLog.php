<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'raw_data',
        'employee_id',
        'timestamp',
        'log_type',
        'processed',
        'error_message',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'processed' => 'boolean',
    ];

    // Relasi ke device
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    // Relasi ke karyawan
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
