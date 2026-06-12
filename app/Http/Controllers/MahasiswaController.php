<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::query();

        // Filter Angkatan
        $angkatan = $request->get('angkatan');
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

        return view('admin.mahasiswa.index', compact('mahasiswas', 'angkatans', 'angkatan'));
    }
}
