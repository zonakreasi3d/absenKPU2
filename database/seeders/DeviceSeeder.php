<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample devices
        Device::create([
            'device_name' => 'Mesin Absensi Utama',
            'device_location' => 'Lobi Utama',
            'device_type' => 'handkey',
            'serial_number' => 'HK001',
            'ip_address' => '192.168.1.100',
            'status' => 'active',
        ]);

        Device::create([
            'device_name' => 'Mesin Absensi Gedung B',
            'device_location' => 'Lantai 2 Gedung B',
            'device_type' => 'handkey',
            'serial_number' => 'HK002',
            'ip_address' => '192.168.1.101',
            'status' => 'active',
        ]);

        Device::create([
            'device_name' => 'Absensi Mobile',
            'device_location' => 'Area Outdoor',
            'device_type' => 'android',
            'serial_number' => 'AND001',
            'ip_address' => null,
            'status' => 'active',
        ]);
    }
}
