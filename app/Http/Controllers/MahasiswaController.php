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
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'csv_file.required' => 'Silakan pilih file CSV terlebih dahulu.',
            'csv_file.mimes' => 'File harus berformat .csv atau .txt',
            'csv_file.max' => 'Ukuran file maksimal 2MB',
        ]);

        $file = $request->file('csv_file');
        
        $handle = fopen($file->getRealPath(), "r");
        
        // Baca header CSV
        $header = fgetcsv($handle, 1000, ",");
        
        // Validasi header sederhana
        if (!$header || strtolower($header[0]) !== 'nim') {
            fclose($handle);
            return back()->with('error', 'Format CSV tidak valid. Pastikan kolom pertama adalah NIM. Silakan unduh template jika ragu.');
        }

        $importedCount = 0;
        $updatedCount = 0;

        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            // Abaikan baris kosong
            if (empty(array_filter($row))) continue;

            $nim = trim($row[0] ?? '');
            $nama = trim($row[1] ?? '');
            $jurusan = trim($row[2] ?? '');
            $semester = trim($row[3] ?? '');

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

        fclose($handle);

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
            // Header row
            fputcsv($file, ['NIM', 'Nama Lengkap', 'Jurusan', 'Semester']);
            // Sample row
            fputcsv($file, ['24.1.9.0001', 'John Doe', 'Teknik Informatika', '4']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
