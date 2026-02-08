# Dokumentasi API Sistem Absensi KPU

## Daftar Isi
1. [Gambaran Umum](#gambaran-umum)
2. [Autentikasi](#autentikasi)
3. [Endpoint API](#endpoint-api)
4. [Format Data](#format-data)
5. [Contoh Penggunaan](#contoh-penggunaan)
6. [Kode Status HTTP](#kode-status-http)
7. [Error Handling](#error-handling)

## Gambaran Umum

Sistem absensi ini menyediakan API untuk menerima data absensi dari perangkat seperti mesin absensi Handkey 2 dan aplikasi Android. API ini dirancang untuk menerima data mentah dari perangkat dan memprosesnya menjadi catatan kehadiran yang terorganisir.

## Autentikasi

API menggunakan autentikasi berbasis token yang dikirim melalui header HTTP:

```
X-API-TOKEN: [token_perangkat]
```

Setiap perangkat harus memiliki token API yang valid untuk dapat mengirimkan data ke sistem.

## Endpoint API

### Menerima Data Absensi dari Perangkat

#### POST `/api/v1/attendance/receive`

Endpoint ini digunakan oleh perangkat absensi untuk mengirimkan data kehadiran.

**Header:**
- `X-API-TOKEN` (wajib): Token API perangkat

**Parameter:**
- `data` (wajib): Data absensi dalam format `ID|tanggal_waktu|in/out`

**Contoh Request:**
```
POST /api/v1/attendance/receive
X-API-TOKEN: abcdef1234567890
Content-Type: application/json

{
  "data": "EMP001|2026-02-08 08:30:00|in"
}
```

**Response Sukses:**
```json
{
  "success": true,
  "message": "Data absensi berhasil diterima",
  "data": {
    "log_id": 123,
    "employee_name": "John Doe",
    "timestamp": "2026-02-08 08:30:00",
    "log_type": "in"
  }
}
```

**Response Gagal:**
```json
{
  "success": false,
  "message": "API token tidak valid"
}
```

## Format Data

### Format Input Data Absensi

Data absensi harus dikirim dalam format berikut:

```
ID|tanggal_waktu|in/out
```

Dimana:
- `ID`: ID karyawan sesuai dengan data di sistem
- `tanggal_waktu`: Tanggal dan waktu dalam format `YYYY-MM-DD HH:MM:SS`
- `in/out`: Jenis log, bisa berupa `in` (masuk) atau `out` (keluar)

Contoh:
- `EMP001|2026-02-08 08:30:00|in`
- `EMP001|2026-02-08 17:00:00|out`

### Struktur Data Internal

Sistem menyimpan data absensi dalam dua tabel utama:

#### attendance_logs
Tabel ini menyimpan data mentah dari perangkat:
- `id`: ID unik log
- `device_id`: ID perangkat yang mengirim data
- `raw_data`: Data mentah dalam format `ID|tanggal_waktu|in/out`
- `employee_id`: ID karyawan yang terkait
- `timestamp`: Waktu kejadian
- `log_type`: Jenis log (`in` atau `out`)
- `processed`: Status apakah data sudah diproses (0/1)

#### attendance_records
Tabel ini menyimpan data absensi yang telah diproses:
- `id`: ID unik rekaman
- `employee_id`: ID karyawan
- `device_id`: ID perangkat yang digunakan
- `check_in_time`: Waktu masuk
- `check_out_time`: Waktu keluar
- `attendance_type`: Jenis kehadiran (`office`, `remote`)
- `status`: Status kehadiran (`present`, `late`, `early_leave`)
- `notes`: Catatan tambahan

## Contoh Penggunaan

### Mengirim Data Masuk (Check-in)

Request:
```
POST /api/v1/attendance/receive
X-API-TOKEN: abcdef1234567890
Content-Type: application/json

{
  "data": "EMP001|2026-02-08 08:30:00|in"
}
```

Response:
```json
{
  "success": true,
  "message": "Data absensi berhasil diterima",
  "data": {
    "log_id": 123,
    "employee_name": "John Doe",
    "timestamp": "2026-02-08 08:30:00",
    "log_type": "in"
  }
}
```

### Mengirim Data Keluar (Check-out)

Request:
```
POST /api/v1/attendance/receive
X-API-TOKEN: abcdef1234567890
Content-Type: application/json

{
  "data": "EMP001|2026-02-08 17:00:00|out"
}
```

Response:
```json
{
  "success": true,
  "message": "Data absensi berhasil diterima",
  "data": {
    "log_id": 124,
    "employee_name": "John Doe",
    "timestamp": "2026-02-08 17:00:00",
    "log_type": "out"
  }
}
```

## Kode Status HTTP

- `200 OK`: Permintaan berhasil diproses
- `400 Bad Request`: Format data tidak valid
- `401 Unauthorized`: Token API tidak valid atau tidak ditemukan
- `404 Not Found`: Karyawan tidak ditemukan
- `422 Unprocessable Entity`: Validasi input gagal
- `500 Internal Server Error`: Kesalahan server saat memproses data

## Error Handling

Sistem menangani berbagai jenis kesalahan:

### Kesalahan Autentikasi
- Jika header `X-API-TOKEN` tidak disertakan
- Jika token API tidak valid atau tidak ditemukan dalam sistem

### Kesalahan Validasi Input
- Jika format data tidak sesuai dengan `ID|tanggal_waktu|in/out`
- Jika tipe log bukan `in` atau `out`
- Jika ID karyawan tidak ditemukan dalam sistem

### Kesalahan Server
- Jika terjadi kesalahan saat memproses data
- Jika terjadi kesalahan pada database

Contoh respons kesalahan:
```json
{
  "success": false,
  "message": "API token tidak ditemukan",
  "errors": {}
}
```

atau

```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "data": ["The data field is required."]
  }
}
```

## Catatan Tambahan

- Setiap perangkat harus memiliki token API yang unik
- Data yang dikirim akan diproses dan disimpan dalam dua tahap: pertama sebagai log mentah, kemudian diproses menjadi rekaman kehadiran
- Sistem akan secara otomatis mengelola waktu masuk dan keluar berdasarkan data yang diterima
- Jika ada data duplikat atau konflik waktu, sistem akan memilih waktu yang paling sesuai (misalnya waktu masuk tercepat dan waktu keluar terlama)