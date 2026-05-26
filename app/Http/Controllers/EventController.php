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
    $poster = null;

    if ($request->hasFile('poster')) {

        $poster = $request->file('poster')
            ->store('event', 'public');
    }

    Event::create([
        'nama_event' => $request->nama_event,
        'tanggal' => $request->tanggal,
        'tempat' => $request->tempat,
        'deskripsi' => $request->deskripsi,
        'poster' => $poster,
    ]);

    return redirect('/admin/event');
}

    public function edit($id)
    {
        $event = Event::findOrFail($id);

        return view('admin.event.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

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

        $event->save();

        return redirect('/admin/event');
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();

        return redirect('/admin/event');
    }
}