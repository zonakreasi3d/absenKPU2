<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            [
                'device_name' => 'Mesin Absen Utama',
                'device_location' => 'Lobby Gedung A',
                'device_type' => 'handkey',
                'serial_number' => 'HK001',
                'ip_address' => '192.168.1.100',
                'status' => 'active',
            ],
            [
                'device_name' => 'Mesin Absen Belakang',
                'device_location' => 'Area Produksi',
                'device_type' => 'handkey',
                'serial_number' => 'HK002',
                'ip_address' => '192.168.1.101',
                'status' => 'active',
            ],
            [
                'device_name' => 'Mesin Absen Parkiran',
                'device_location' => 'Area Parkir',
                'device_type' => 'handkey',
                'serial_number' => 'HK003',
                'ip_address' => '192.168.1.102',
                'status' => 'active',
            ],
        ];

        foreach ($devices as $device) {
            $deviceModel = Device::create($device);
            // Generate API token untuk setiap device
            $deviceModel->generateApiToken();
        }

        $this->command->info('Data mesin absensi dummy berhasil dibuat.');
    }
}