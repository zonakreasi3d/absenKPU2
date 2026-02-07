@extends('layouts.app')

@section('title', 'Edit Permintaan Absensi')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Permintaan Absensi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.requests.update', $request) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="request_type" class="form-label">Jenis Permintaan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('request_type') is-invalid @enderror" id="request_type" name="request_type" required>
                                        <option value="">Pilih Jenis Permintaan</option>
                                        <option value="remote_work" {{ old('request_type', $request->request_type) == 'remote_work' ? 'selected' : '' }}>Kerja Remote</option>
                                        <option value="business_trip" {{ old('request_type', $request->request_type) == 'business_trip' ? 'selected' : '' }}>Dinas Luar</option>
                                    </select>
                                    @error('request_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="request_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('request_date') is-invalid @enderror" id="request_date" name="request_date" value="{{ old('request_date', $request->request_date->format('Y-m-d')) }}" required>
                                    @error('request_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Jam Mulai</label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', $request->start_time) }}">
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">Jam Selesai</label>
                                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', $request->end_time) }}">
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3" id="location-field" style="@if(old('request_type', $request->request_type) !== 'remote_work') display: none; @endif">
                            <label for="location" class="form-label">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $request->location) }}">
                            <div class="form-text">Contoh: Koordinat GPS atau alamat lengkap</div>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="reason" class="form-label">Alasan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="3" required>{{ old('reason', $request->reason) }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="photo" class="form-label">Upload Bukti/Foto</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                            <div class="form-text">Format: jpeg, png, jpg | Ukuran maks: 2MB</div>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if($request->photo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$request->photo) }}" alt="Bukti" class="img-thumbnail" style="width: 200px; height: auto;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo" value="1">
                                        <label class="form-check-label" for="remove_photo">
                                            Hapus foto saat ini
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted"><span class="text-danger">*</span> Wajib diisi</small>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('employee.requests.index') }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Update Permintaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const requestTypeSelect = document.getElementById('request_type');
    const locationField = document.getElementById('location-field');
    
    requestTypeSelect.addEventListener('change', function() {
        if (this.value === 'remote_work') {
            locationField.style.display = 'block';
            document.getElementById('location').setAttribute('required', 'required');
        } else {
            locationField.style.display = 'none';
            document.getElementById('location').removeAttribute('required');
        }
    });
    
    // Trigger change event on page load to set initial state
    requestTypeSelect.dispatchEvent(new Event('change'));
});
</script>
@endsection