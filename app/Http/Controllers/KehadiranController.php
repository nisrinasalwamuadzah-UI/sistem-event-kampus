<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Kehadiran;

class KehadiranController extends Controller
{
    public function scan()
    {
        $events = \App\Models\Event::where('status', 'Aktif')->get();
        return view('admin.scan', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'event_id' => 'required|exists:events,id'
        ]);

        try {
            $nim_input = trim($request->nim);
            // Hapus semua karakter titik, spasi, atau strip dari input scanner
            $clean_nim = str_replace(['.', ' ', '-'], '', $nim_input);
            
            // Cari mahasiswa dengan mengabaikan titik di database
            $mhs = Mahasiswa::whereRaw("REPLACE(nim, '.', '') = ?", [$clean_nim])->first();

            if (!$mhs) {
                return back()->with('error', 'Mahasiswa tidak ditemukan di database kampus');
            }

            // Cek Eligibility (Apakah mahasiswa ini didaftarkan ke event ini?)
            $isRegistered = \Illuminate\Support\Facades\DB::table('event_mahasiswa')
                ->where('event_id', $request->event_id)
                ->where('nim', $mhs->nim)
                ->exists();

            if (!$isRegistered) {
                return back()->with('error', 'Akses Ditolak: Mahasiswa ini tidak terdaftar sebagai peserta event ini');
            }

            $exists = Kehadiran::where('nim', $mhs->nim)->where('event_id', $request->event_id)->exists();
            if ($exists) {
                return back()->with('error', 'Mahasiswa sudah absen untuk event ini');
            }

            Kehadiran::create([
                'event_id' => $request->event_id,
                'nim' => $mhs->nim,
                'nama' => $mhs->nama,
                'jurusan' => $mhs->jurusan,
                'semester' => $mhs->semester,
                'waktu_scan' => now(),
            ]);

            return back()->with('success', 'Absensi berhasil');

        } catch (\Exception $e) {
            // TANGKAP ERROR 500 SECARA MANUAL DAN TAMPILKAN KE LAYAR
            return back()->with('error', 'CRITICAL ERROR: ' . $e->getMessage());
        }
    }

    public function exportPimpinan($event_id = null)
    {
        if (session('role') != 'pimpinan') {
            abort(403, 'Unauthorized action.');
        }

        $query = Kehadiran::with('event')->latest('waktu_scan');
        
        if ($event_id) {
            $query->where('event_id', $event_id);
            $event = \App\Models\Event::find($event_id);
            $filename = 'Laporan_Kehadiran_' . str_replace(' ', '_', $event->nama_event) . '_' . date('Ymd_His') . '.csv';
        } else {
            $filename = 'Laporan_Semua_Kehadiran_' . date('Ymd_His') . '.csv';
        }

        $kehadirans = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'Pragma' => 'public'
        ];

        $callback = function () use ($kehadirans) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");
            
            // CSV Headers
            fputcsv($file, [
                'No', 
                'Nama Event', 
                'Tanggal Event', 
                'NIM', 
                'Nama Lengkap', 
                'Jurusan', 
                'Semester', 
                'Waktu Scan Absensi', 
                'Status'
            ]);

            $no = 1;
            foreach ($kehadirans as $k) {
                // Formatting NIM with explicit string cast or enclosure isn't strictly needed if we have BOM and standard fputcsv, 
                // but prepending an apostrophe or just using fputcsv is usually enough. fputcsv wraps strings with quotes.
                // Excel handles string numbers better if they are enclosed or have explicit formatting, but standard text is fine.
                fputcsv($file, [
                    $no++,
                    $k->event ? $k->event->nama_event : '-',
                    $k->event ? $k->event->tanggal : '-',
                    ' ' . $k->nim, // Space prevents Excel from converting NIM to number
                    $k->nama,
                    $k->jurusan,
                    $k->semester,
                    $k->waktu_scan->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s') . ' WIB',
                    'Hadir'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}