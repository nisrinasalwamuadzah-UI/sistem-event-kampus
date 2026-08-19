<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Mahasiswa;
use App\Models\Kehadiran;

class PimpinanDashboardController extends Controller
{
    public function index()
    {
        $events = Event::latest()->take(6)->get();
        $totalEvent = Event::count();
        $totalHadir = Kehadiran::count();
        $totalMahasiswa = Mahasiswa::count();
        
        // Asumsi sederhana: total tidak hadir = (total event * total mahasiswa) - total hadir
        // Namun, jika tidak semua mahasiswa terdaftar di setiap event, ini perlu disesuaikan.
        // Berdasarkan logika sistem yang lebih kompleks, kita bisa mengambil `totalRegistered` dari tabel pivot `event_mahasiswa`.
        // Untuk saat ini, kita gunakan pendekatan sederhana:
        $totalTerdaftar = \Illuminate\Support\Facades\DB::table('event_mahasiswa')->count();
        $totalTidakHadir = max($totalTerdaftar - $totalHadir, 0);

        return view('pimpinan.dashboard', compact(
            'events',
            'totalEvent',
            'totalHadir',
            'totalMahasiswa',
            'totalTidakHadir'
        ));
    }
}
