<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Absensi Karyawan</h1>
        <p>Periode: {{ date('d M Y') }}</p>
        <p>Dicetak pada: {{ date('d M Y H:i:s') }}</p>
    </div>
    
    <table>
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
    
    <div class="footer">
        <p>Laporan dicetak oleh sistem</p>
    </div>
</body>
</html>