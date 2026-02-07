<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_name',
        'device_location',
        'device_type',
        'serial_number',
        'ip_address',
        'status',
        'api_token',
    ];

    // Relasi ke data absensi
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'device_id');
    }

    // Method untuk generate API token
    public function generateApiToken()
    {
        $this->api_token = bin2hex(random_bytes(40));
        $this->save();
        return $this->api_token;
    }
}
