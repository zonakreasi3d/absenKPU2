<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Employee;
use App\Models\AttendanceLog;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Receive attendance data from device
     * Format: ID|tanggal_waktu|in/out
     */
    public function receive(Request $request)
    {
        // Validasi header API
        $deviceToken = $request->header('X-API-TOKEN');
        
        if (!$deviceToken) {
            return response()->json([
                'success' => false,
                'message' => 'API token tidak ditemukan'
            ], 401);
        }

        // Cari device berdasarkan token
        $device = Device::where('api_token', $deviceToken)->first();
        
        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'API token tidak valid'
            ], 401);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'data' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $rawData = $request->input('data');
        
        try {
            // Parse data: ID|tanggal_waktu|in/out
            $parts = explode('|', $rawData);
            
            if (count($parts) !== 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format data tidak valid. Harus: ID|tanggal_waktu|in/out'
                ], 400);
            }

            $employeeId = $parts[0];
            $timestamp = $parts[1];
            $logType = strtolower($parts[2]);

            // Validasi log_type
            if (!in_array($logType, ['in', 'out'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipe log tidak valid. Harus: in atau out'
                ], 400);
            }

            // Ubah format timestamp jika perlu
            // Format yang diterima: Y-m-d H:i:s atau format lain yang valid
            $carbonTimestamp = \Carbon\Carbon::parse($timestamp);

            // Cari karyawan berdasarkan employee_id
            $employee = Employee::where('employee_id', $employeeId)->first();
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => "Karyawan dengan ID {$employeeId} tidak ditemukan"
                ], 404);
            }

            // Simpan ke attendance_logs (raw data)
            $attendanceLog = AttendanceLog::create([
                'device_id' => $device->id,
                'raw_data' => $rawData,
                'employee_id' => $employee->id,
                'timestamp' => $carbonTimestamp,
                'log_type' => $logType,
                'processed' => false,
            ]);

            // Proses dan simpan ke attendance_records
            $this->processAttendanceRecord($attendanceLog);

            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil diterima',
                'data' => [
                    'log_id' => $attendanceLog->id,
                    'employee_name' => $employee->name,
                    'timestamp' => $carbonTimestamp->format('Y-m-d H:i:s'),
                    'log_type' => $logType
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process attendance record from log
     */
    private function processAttendanceRecord($attendanceLog)
    {
        $employee = $attendanceLog->employee;
        $device = $attendanceLog->device;
        $timestamp = $attendanceLog->timestamp;
        $logType = $attendanceLog->log_type;

        // Cari atau buat attendance record untuk tanggal ini
        $date = $timestamp->toDateString();
        $existingRecord = AttendanceRecord::where('employee_id', $employee->id)
            ->whereDate('check_in_time', $date)
            ->first();

        if ($logType === 'in') {
            // Jika ini adalah check-in
            if ($existingRecord) {
                // Update waktu check-in jika belum ada atau lebih awal
                if (!$existingRecord->check_in_time || $timestamp->lt($existingRecord->check_in_time)) {
                    $existingRecord->update([
                        'check_in_time' => $timestamp,
                        'device_id' => $device->id,
                    ]);
                }
            } else {
                // Buat record baru
                AttendanceRecord::create([
                    'employee_id' => $employee->id,
                    'device_id' => $device->id,
                    'check_in_time' => $timestamp,
                ]);
            }
        } elseif ($logType === 'out') {
            // Jika ini adalah check-out
            if ($existingRecord) {
                // Update waktu check-out jika belum ada atau lebih lambat
                if (!$existingRecord->check_out_time || $timestamp->gt($existingRecord->check_out_time)) {
                    $existingRecord->update([
                        'check_out_time' => $timestamp,
                    ]);
                }
            } else {
                // Jika tidak ada record check-in sebelumnya, buat record baru dengan check-out saja
                AttendanceRecord::create([
                    'employee_id' => $employee->id,
                    'device_id' => $device->id,
                    'check_out_time' => $timestamp,
                ]);
            }
        }

        // Tandai log sebagai telah diproses
        $attendanceLog->update(['processed' => true]);
    }
}
