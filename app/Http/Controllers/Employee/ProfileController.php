<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = auth()->user();
        $employee = $user->employee;
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Data karyawan tidak ditemukan.');
        }
        
        return view('employee.profile.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = auth()->user();
        $employee = $user->employee;
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Data karyawan tidak ditemukan.');
        }
        
        $departments = Department::all();
        
        return view('employee.profile.edit', compact('employee', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:15',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'required',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Verifikasi password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Update data karyawan
        $employeeData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department_id' => $request->department_id,
            'position' => $request->position,
        ];

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            
            $employeeData['photo'] = $request->file('photo')->store('employees', 'public');
        } elseif ($request->has('remove_photo') && $request->remove_photo) {
            // Hapus foto jika pengguna memilih untuk menghapusnya
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
                $employeeData['photo'] = null;
            }
        } else {
            unset($employeeData['photo']); // Jangan update kolom photo jika tidak ada file baru
        }

        $employee->update($employeeData);

        // Update password jika disediakan
        if ($request->filled('new_password')) {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
        }

        return redirect()->route('employee.profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
