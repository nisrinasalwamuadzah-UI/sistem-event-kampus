<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class KehadiranController extends Controller
{
    public function index()
    {
        $events = \App\Models\Event::withCount('kehadirans')->latest()->get();
        return view('admin.kehadiran', compact('events'));
    }

    public function show($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        $kehadirans = $event->kehadirans()->latest()->get();
        $totalRegistered = \Illuminate\Support\Facades\DB::table('event_mahasiswa')->where('event_id', $id)->count();
        $presentCount = $kehadirans->count();
        $absentCount = max($totalRegistered - $presentCount, 0);
        return view('admin.kehadiran', compact('event', 'kehadirans', 'totalRegistered', 'presentCount', 'absentCount'));
    }

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
                $msg = 'Data Tidak Ditemukan: NIM ' . $clean_nim . ' tidak terdaftar di Database Induk Kampus!';
                return $request->wantsJson() ? response()->json(['status' => 'error', 'message' => $msg]) : back()->with('error', $msg);
            }

            // Cek Eligibility (Apakah mahasiswa ini didaftarkan ke event ini?)
            $isRegistered = \Illuminate\Support\Facades\DB::table('event_mahasiswa')
                ->where('event_id', $request->event_id)
                ->where('nim', $mhs->nim)
                ->exists();

            if (!$isRegistered) {
                $msg = 'Akses Ditolak: ' . $mhs->nama . ' TIDAK TERDAFTAR sebagai peserta di event ini!';
                return $request->wantsJson() ? response()->json(['status' => 'error', 'message' => $msg]) : back()->with('error', $msg);
            }

            $exists = Kehadiran::where('nim', $mhs->nim)->where('event_id', $request->event_id)->exists();
            if ($exists) {
                $msg = 'Absensi Ganda: ' . $mhs->nama . ' SUDAH melakukan absensi sebelumnya!';
                return $request->wantsJson() ? response()->json(['status' => 'error', 'message' => $msg]) : back()->with('error', $msg);
            }

            Kehadiran::create([
                'event_id' => $request->event_id,
                'nim' => $mhs->nim,
                'nama' => $mhs->nama,
                'jurusan' => $mhs->jurusan,
                'semester' => $mhs->semester,
                'waktu_scan' => now(),
            ]);

            $msg = 'Absensi Berhasil: ' . $mhs->nama . ' (' . $mhs->jurusan . ')';
            return $request->wantsJson() ? response()->json(['status' => 'success', 'message' => $msg]) : back()->with('success', $msg);

        } catch (\Exception $e) {
            // TANGKAP ERROR 500 SECARA MANUAL DAN TAMPILKAN KE LAYAR
            $msg = 'CRITICAL ERROR: ' . $e->getMessage();
            return $request->wantsJson() ? response()->json(['status' => 'error', 'message' => $msg], 500) : back()->with('error', $msg);
        }
    }

    public function exportPdf($eventId)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'pimpinan'])) {
            abort(403, 'Unauthorized action.');
        }

        $event = \App\Models\Event::findOrFail($eventId);
        $kehadirans = Kehadiran::where('event_id', $eventId)->latest('waktu_scan')->get();

        $filename = 'Laporan_Kehadiran_' . Str::slug($event->nama_event, '_') . '_' . date('Ymd_His') . '.pdf';
        $pdf = Pdf::loadView('admin.kehadiran_pdf', compact('event', 'kehadirans'));

        return $pdf->download($filename);
    }

    public function savePdf($eventId)
    {
        if (!auth()->check() || auth()->user()->role != 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $event = \App\Models\Event::findOrFail($eventId);
        $kehadirans = Kehadiran::where('event_id', $eventId)->latest('waktu_scan')->get();

        $filename = 'Laporan_Kehadiran_' . Str::slug($event->nama_event, '_') . '_' . date('Ymd_His') . '.pdf';
        $pdf = Pdf::loadView('admin.kehadiran_pdf', compact('event', 'kehadirans'));

        Storage::disk('public')->makeDirectory('kehadiran');
        Storage::disk('public')->put('kehadiran/' . $filename, $pdf->output());

        return back()->with('success', 'Laporan PDF berhasil disimpan.')->with('pdf_path', asset('storage/kehadiran/' . $filename));
    }

    public function exportPimpinan($event_id = null)
    {
        if (!auth()->check() || auth()->user()->role != 'pimpinan') {
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