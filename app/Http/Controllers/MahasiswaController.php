<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::query();

        $eventId = $request->get('event_id');
        $angkatan = $request->get('angkatan');

        if (!$eventId) {
            $latestEvent = \App\Models\Event::latest()->first();
            if ($latestEvent) {
                $eventId = $latestEvent->id;
            }
        }

        if ($eventId) {
            $registeredNims = \Illuminate\Support\Facades\DB::table('event_mahasiswa')
                ->where('event_id', $eventId)
                ->pluck('nim')
                ->toArray();

            if (!empty($registeredNims)) {
                $query->whereIn('nim', $registeredNims);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Filter Angkatan
        if ($angkatan) {
            // Asumsi: 2 digit pertama NIM adalah angkatan, misal: 23190012 -> Angkatan 23
            $prefix = substr($angkatan, -2); // Ambil '23' dari '2023'
            
            if ($prefix == '23') {
                $query->where(function($q) use ($prefix) {
                    $q->where('nim', 'like', $prefix . '%')
                      ->orWhere('nim', 'like', '22%'); // Pengecualian Misbahudin (NIM awal 22)
                });
            } else {
                $query->where('nim', 'like', $prefix . '%')
                      ->where('nim', 'not like', '22%'); // Pastikan tidak muncul di angkatan lain
            }
        }

        $mahasiswas = $query->get();

        $events = \App\Models\Event::latest()->get();

        // Get unique cohort prefixes for the dropdown filter dynamically
        $allNims = Mahasiswa::select('nim')->pluck('nim');
        $angkatans = [];
        foreach($allNims as $nim) {
            if(strlen($nim) >= 2) {
                $prefix = substr($nim, 0, 2);
                $year = "20" . $prefix; // Asumsi tahun 20xx
                
                // Pengecualian: Misbahudin (NIM 22...) digabungkan ke 2023
                if ($prefix == '22') {
                    $year = "2023";
                }

                if (!isset($angkatans[$year])) {
                    $angkatans[$year] = $year;
                }
            }
        }
        arsort($angkatans);

        return view('admin.mahasiswa.index', compact('mahasiswas', 'angkatans', 'angkatan', 'events', 'eventId'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
        ], [
            'csv_file.required' => 'Silakan pilih file terlebih dahulu.',
            'csv_file.mimes' => 'File harus berformat .csv, .txt, .xlsx, atau .xls',
            'csv_file.max' => 'Ukuran file maksimal 5MB',
        ]);

        $file = $request->file('csv_file');
        
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        if (count($rows) < 1) {
            return back()->with('error', 'File kosong atau tidak valid.');
        }

        // 1. Cari Baris Header (Maksimal cari di 10 baris pertama untuk toleransi baris kosong)
        $headerRowIndex = -1;
        $colMap = [
            'nim' => -1,
            'nama' => -1,
            'jurusan' => -1,
            'semester' => -1,
        ];

        for ($i = 0; $i < min(10, count($rows)); $i++) {
            $row = $rows[$i];
            foreach ($row as $colIndex => $cellValue) {
                $val = strtolower(trim($cellValue ?? ''));
                if ($val === 'nim') $colMap['nim'] = $colIndex;
                elseif ($val === 'nama' || $val === 'nama lengkap') $colMap['nama'] = $colIndex;
                elseif ($val === 'jurusan' || $val === 'prodi' || $val === 'program studi') $colMap['jurusan'] = $colIndex;
                elseif (str_contains($val, 'semester') || str_contains($val, 'kelas')) $colMap['semester'] = $colIndex;
            }

            // Jika NIM ditemukan, kita asumsikan ini adalah baris header
            if ($colMap['nim'] !== -1) {
                $headerRowIndex = $i;
                break;
            }
        }

        if ($headerRowIndex === -1) {
            return back()->with('error', 'Format tidak valid. Tidak dapat menemukan kolom "NIM". Pastikan penamaan header sesuai template.');
        }

        $importedCount = 0;
        $updatedCount = 0;

        // Loop data mulai dari baris setelah header
        for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            // Abaikan baris jika kolom NIM dan Nama kosong
            if (empty(trim($row[$colMap['nim']] ?? '')) && empty(trim($row[$colMap['nama']] ?? ''))) {
                continue;
            }

            $nim = trim($row[$colMap['nim']] ?? '');
            
            // Jika kolom lain tidak ditemukan di header, nilainya kosong
            $nama = $colMap['nama'] !== -1 ? trim($row[$colMap['nama']] ?? '') : '';
            $jurusan = $colMap['jurusan'] !== -1 ? trim($row[$colMap['jurusan']] ?? '') : '';
            $semester = $colMap['semester'] !== -1 ? trim($row[$colMap['semester']] ?? '') : '';

            if (empty($nim) || empty($nama)) continue;

            $mahasiswa = Mahasiswa::where('nim', $nim)->first();

            if ($mahasiswa) {
                // Update
                $mahasiswa->update([
                    'nama' => $nama,
                    'jurusan' => $jurusan,
                    'semester' => $semester
                ]);
                $updatedCount++;
            } else {
                // Create
                Mahasiswa::create([
                    'nim' => $nim,
                    'nama' => $nama,
                    'jurusan' => $jurusan,
                    'semester' => $semester
                ]);
                $importedCount++;
            }
        }

        return back()->with('success', "Import berhasil! $importedCount data baru ditambahkan, $updatedCount data diperbarui.");
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Template_Import_Mahasiswa.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            // Header row (Ditambahkan Nomor di awal sesuai permintaan)
            fputcsv($file, ['No', 'NIM', 'Nama Lengkap', 'Program Studi', 'Kelas/Semester'], ';');
            // Sample row
            fputcsv($file, ['1', '24.1.9.0001', 'John Doe', 'Teknik Informatika', 'PAGI'], ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
