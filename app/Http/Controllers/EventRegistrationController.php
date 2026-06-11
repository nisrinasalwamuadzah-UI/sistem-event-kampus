<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Mahasiswa;

class EventRegistrationController extends Controller
{
    public function showRegistrationForm($id)
    {
        $event = Event::findOrFail($id);
        
        // Prevent registration if event is closed
        if ($event->status == 'Selesai') {
            return abort(403, 'Event sudah selesai. Pendaftaran ditutup.');
        }

        return view('event.register', compact('event'));
    }

    public function processRegistration(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        if ($event->status == 'Selesai') {
            return abort(403, 'Event sudah selesai.');
        }

        $request->validate([
            'nim' => 'required'
        ]);

        $nim_input = trim($request->nim);
        
        // Strip dots for flexible checking
        $clean_nim = str_replace(['.', ' ', '-'], '', $nim_input);
        
        $mhs = Mahasiswa::whereRaw("REPLACE(nim, '.', '') = ?", [$clean_nim])->first();

        if (!$mhs) {
            return back()->with('error', 'NIM tidak terdaftar dalam database Mahasiswa.');
        }

        // Generate Ticket Data
        // Store the exact NIM format from the database into the session so the ticket prints exactly as in DB
        return redirect('/event/'.$event->id.'/ticket')->with('ticket_nim', $mhs->nim)->with('ticket_nama', $mhs->nama);
    }

    public function showTicket($id)
    {
        $event = Event::findOrFail($id);
        
        if (!session('ticket_nim')) {
            return redirect('/event/'.$event->id.'/register');
        }

        $ticket_nim = session('ticket_nim');
        $ticket_nama = session('ticket_nama');

        // Generate SVG secara inline. Ini menjamin html2canvas bisa merendernya
        // karena SVG adalah elemen HTML native (path/rect), bukan file gambar external.
        $qr_svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(220)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($ticket_nim);

        return view('event.ticket', compact('event', 'ticket_nim', 'ticket_nama', 'qr_svg'));
    }
}
