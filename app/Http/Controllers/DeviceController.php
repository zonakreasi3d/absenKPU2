<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::all();
        return view('devices.index', compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('devices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'device_name' => 'required|string|max:255',
            'device_location' => 'required|string|max:255',
            'device_type' => 'required|in:handkey,android',
            'serial_number' => 'required|unique:devices,serial_number',
            'ip_address' => 'nullable|ip',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        Device::create($request->all());

        return redirect()->route('devices.index')->with('success', 'Mesin absensi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        return view('devices.show', compact('device'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        return view('devices.edit', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        $request->validate([
            'device_name' => 'required|string|max:255',
            'device_location' => 'required|string|max:255',
            'device_type' => 'required|in:handkey,android',
            'serial_number' => 'required|unique:devices,serial_number,'.$device->id,
            'ip_address' => 'nullable|ip',
            'status' => 'required|in:active,inactive,maintenance',
        ]);

        $device->update($request->all());

        return redirect()->route('devices.index')->with('success', 'Mesin absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()->route('devices.index')->with('success', 'Mesin absensi berhasil dihapus.');
    }

    /**
     * Generate API token for the device
     */
    public function generateApiToken(Device $device)
    {
        $apiToken = $device->generateApiToken();

        return redirect()->back()->with('success', 'API token berhasil digenerate: ' . $apiToken);
    }
}
