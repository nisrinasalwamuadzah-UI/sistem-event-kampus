<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Mahasiswa;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\Session;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // AdminMiddleware handles the check, but we can leave this or remove it.
        // I will remove the manual check since AdminMiddleware covers it.

        $events = Event::latest()->take(6)->get();
        $totalEvent = Event::count();
        $totalHadir = Kehadiran::count();
        $totalTidakHadir = 0; // Or calculate if needed

        return view('admin.dashboard', compact(
            'events',
            'totalEvent',
            'totalHadir',
            'totalTidakHadir'
        ));
    }
}
