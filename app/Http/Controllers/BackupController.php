<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    /**
     * Display a listing of the backups.
     */
    public function index()
    {
        $backupDir = storage_path('app/backups');
        
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }
        
        $files = File::files($backupDir);
        
        $backups = [];
        foreach ($files as $file) {
            $backups[] = [
                'filename' => $file->getFilename(),
                'size' => $file->getSize(),
                'modified' => $file->getMTime(),
                'path' => $file->getPathname(),
            ];
        }
        
        // Urutkan berdasarkan waktu modifikasi (terbaru dulu)
        usort($backups, function ($a, $b) {
            return $b['modified'] <=> $a['modified'];
        });

        return view('backup.index', compact('backups'));
    }

    /**
     * Create a new backup.
     */
    public function create()
    {
        try {
            // Membuat direktori backup jika belum ada
            $backupDir = storage_path('app/backups');
            if (!File::exists($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }
            
            // Nama file backup dengan timestamp
            $fileName = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupDir . '/' . $fileName;
            
            // Mendapatkan informasi database dari konfigurasi
            $dbConfig = config('database.connections.mysql');
            
            // Perintah mysqldump
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s --routines --single-transaction %s > %s',
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['port']),
                escapeshellarg($dbConfig['username']),
                escapeshellarg($dbConfig['password']),
                escapeshellarg($dbConfig['database']),
                escapeshellarg($filePath)
            );
            
            // Eksekusi perintah
            $output = [];
            $resultCode = 0;
            exec($command, $output, $resultCode);
            
            if ($resultCode === 0) {
                return redirect()->route('backup.index')->with('success', 'Backup berhasil dibuat: ' . $fileName);
            } else {
                return redirect()->route('backup.index')->with('error', 'Backup gagal: Perintah mysqldump gagal dieksekusi');
            }
        } catch (\Exception $e) {
            return redirect()->route('backup.index')->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }

    /**
     * Download a backup file.
     */
    public function download($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (!File::exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }
        
        return response()->download($path);
    }

    /**
     * Delete a backup file.
     */
    public function destroy($filename)
    {
        try {
            $path = storage_path('app/backups/' . $filename);
            
            if (File::exists($path)) {
                File::delete($path);
            }
            
            return redirect()->route('backup.index')->with('success', 'File backup berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('backup.index')->with('error', 'Hapus backup gagal: ' . $e->getMessage());
        }
    }
}
