<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kehadiran;

class ScanController extends Controller
{
    // tampil halaman scan
    public function index()
    {
        return view('admin.scan');
    }

    // proses hasil scan
    public function store(Request $request)
    {
        $qr = $request->qr_data;

        // pecah data dari QR
        $data = explode('|', $qr);

        // validasi sederhana
        if (count($data) != 4) {
            return response()->json([
                'status' => 'error',
                'message' => 'Format QR salah'
            ]);
        }

        $nim = $data[0];
        $nama = $data[1];
        $jurusan = $data[2];
        $semester = $data[3];

        // simpan ke database
        Kehadiran::create([
            'nim' => $nim,
            'nama' => $nama,
            'jurusan' => $jurusan,
            'semester' => $semester,
            'waktu_scan' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'nim' => $nim,
            'nama' => $nama,
            'jurusan' => $jurusan,
            'semester' => $semester
        ]);
    }
}