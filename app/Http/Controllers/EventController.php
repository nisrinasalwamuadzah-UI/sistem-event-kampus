<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->get();

        return view('admin.event.index', compact('events'));
    }

    public function create()
    {
        return view('admin.event.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tempat' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
        ]);

        $poster = $request->file('poster')->store('event', 'public');

        Event::create([
            'nama_event' => $request->nama_event,
            'tanggal' => $request->tanggal,
            'tempat' => $request->tempat,
            'deskripsi' => $request->deskripsi,
            'poster' => $poster,
        ]);

        return redirect('/admin/event')->with('success', 'Event berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);

        return view('admin.event.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tempat' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
        ]);

        if ($request->hasFile('poster')) {
            $poster = $request->file('poster')->store('event', 'public');
            $event->poster = $poster;
        }

        $event->update([
            'nama_event' => $request->nama_event,
            'tanggal' => $request->tanggal,
            'tempat' => $request->tempat,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect('/admin/event')->with('success', 'Event berhasil diupdate!');
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();

        return redirect('/admin/event');
    }

    public function peserta($id)
    {
        $event = Event::findOrFail($id);
        $mahasiswas = \App\Models\Mahasiswa::all();
        
        // Get array of registered NIMs for this event
        $registeredNims = \Illuminate\Support\Facades\DB::table('event_mahasiswa')
            ->where('event_id', $id)
            ->pluck('nim')
            ->toArray();

        return view('admin.event.peserta', compact('event', 'mahasiswas', 'registeredNims'));
    }

    public function updatePeserta(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        // nims is an array of checked NIMs from the form
        $nims = $request->input('nims', []);

        // Delete all existing participants for this event
        \Illuminate\Support\Facades\DB::table('event_mahasiswa')->where('event_id', $id)->delete();

        // Insert new ones
        $insertData = [];
        foreach ($nims as $nim) {
            $insertData[] = [
                'event_id' => $id,
                'nim' => $nim,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (count($insertData) > 0) {
            \Illuminate\Support\Facades\DB::table('event_mahasiswa')->insert($insertData);
        }

        return redirect('/admin/event')->with('success', 'Daftar peserta event berhasil diupdate!');
    }
}