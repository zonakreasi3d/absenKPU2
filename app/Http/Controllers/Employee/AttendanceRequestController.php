<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttendanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Ambil data employee terkait dengan user
        $employee = $user->employee;
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Data karyawan tidak ditemukan.');
        }
        
        // Ambil permintaan absensi karyawan ini
        $requests = AttendanceRequest::where('employee_id', $employee->id)
            ->latest()
            ->paginate(10);

        return view('employee.requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employee.requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $request->validate([
            'request_type' => 'required|in:remote_work,business_trip',
            'request_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'required_if:request_type,remote_work|string|max:255',
            'reason' => 'required|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['employee_id'] = $employee->id;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('attendance_requests', 'public');
        }

        AttendanceRequest::create($data);

        return redirect()->route('employee.requests.index')->with('success', 'Permintaan absensi berhasil diajukan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendanceRequest $request)
    {
        // Pastikan hanya pemilik yang bisa melihat
        $user = auth()->user();
        $employee = $user->employee;
        
        if ($request->employee_id !== $employee->id) {
            abort(403, 'Unauthorized');
        }
        
        return view('employee.requests.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendanceRequest $request)
    {
        // Pastikan hanya permintaan yang belum disetujui yang bisa diedit
        if ($request->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya permintaan yang belum diproses yang bisa diedit.');
        }
        
        // Pastikan hanya pemilik yang bisa mengedit
        $user = auth()->user();
        $employee = $user->employee;
        
        if ($request->employee_id !== $employee->id) {
            abort(403, 'Unauthorized');
        }
        
        return view('employee.requests.edit', compact('request'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttendanceRequest $attendanceRequest)
    {
        // Pastikan hanya permintaan yang belum disetujui yang bisa diupdate
        if ($attendanceRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya permintaan yang belum diproses yang bisa diupdate.');
        }
        
        // Pastikan hanya pemilik yang bisa mengupdate
        $user = auth()->user();
        $employee = $user->employee;
        
        if ($attendanceRequest->employee_id !== $employee->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'request_type' => 'required|in:remote_work,business_trip',
            'request_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'required_if:request_type,remote_work|string|max:255',
            'reason' => 'required|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($attendanceRequest->photo) {
                Storage::disk('public')->delete($attendanceRequest->photo);
            }
            
            $data['photo'] = $request->file('photo')->store('attendance_requests', 'public');
        } elseif ($request->has('remove_photo') && $request->remove_photo) {
            // Hapus foto jika pengguna memilih untuk menghapusnya
            if ($attendanceRequest->photo) {
                Storage::disk('public')->delete($attendanceRequest->photo);
                $data['photo'] = null;
            }
        } else {
            unset($data['photo']); // Jangan update kolom photo jika tidak ada file baru
        }

        $attendanceRequest->update($data);

        return redirect()->route('employee.requests.index')->with('success', 'Permintaan absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceRequest $attendanceRequest)
    {
        // Pastikan hanya permintaan yang belum disetujui yang bisa dihapus
        if ($attendanceRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya permintaan yang belum diproses yang bisa dihapus.');
        }
        
        // Pastikan hanya pemilik yang bisa menghapus
        $user = auth()->user();
        $employee = $user->employee;
        
        if ($attendanceRequest->employee_id !== $employee->id) {
            abort(403, 'Unauthorized');
        }

        // Hapus foto jika ada
        if ($attendanceRequest->photo) {
            Storage::disk('public')->delete($attendanceRequest->photo);
        }

        $attendanceRequest->delete();

        return redirect()->route('employee.requests.index')->with('success', 'Permintaan absensi berhasil dihapus.');
    }
}
