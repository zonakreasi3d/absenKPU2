<table class="table">
    <thead>
        <tr>
            <th>Nama Karyawan</th>
            <th>ID Karyawan</th>
            <th>Departemen</th>
            <th>Waktu Masuk</th>
            <th>Waktu Pulang</th>
            <th>Tipe Absensi</th>
            <th>Status</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendances as $attendance)
        <tr>
            <td>{{ $attendance->employee->name }}</td>
            <td>{{ $attendance->employee->employee_id }}</td>
            <td>{{ $attendance->employee->department ? $attendance->employee->department->department_name : '-' }}</td>
            <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('d M Y H:i') : '-' }}</td>
            <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('d M Y H:i') : '-' }}</td>
            <td>{{ ucfirst($attendance->attendance_type) }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $attendance->status)) }}</td>
            <td>{{ $attendance->notes ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>