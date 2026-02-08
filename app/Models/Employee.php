<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'name',
        'email',
        'phone',
        'department_id',
        'position',
        'photo',
        'status',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke departemen
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // Relasi ke data absensi
    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'employee_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'employee_id');
    }
}
