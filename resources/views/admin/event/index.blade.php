@extends('layouts.admin')

@section('title', 'Kelola Event')

@section('content')

    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Kelola Event</h2>
            <p>Daftar kegiatan kampus</p>
        </div>
        <a href="{{ url('/admin/event/create') }}" class="btn btn-primary">
            <i class="ph-bold ph-plus"></i> Tambah Event
        </a>
    </div>

    <!-- EVENT CARDS -->
    <div class="event-grid">

        @foreach($events as $event)
        <div class="event-card">
            <img src="{{ asset('storage/'.$event->poster) }}" alt="poster">
            
            <div class="event-content">
                <div class="event-title">
                    {{ $event->nama_event }}
                </div>

                <div class="event-info">
                    <i class="ph-bold ph-calendar-blank"></i>
                    <span>{{ $event->tanggal }}</span>
                </div>

                <div class="event-actions">
                    <div style="display: flex; gap: 8px; margin-bottom: 8px; width: 100%;">
                        <a href="{{ url('/admin/event/'.$event->id.'/peserta') }}" class="btn btn-sm btn-primary" style="flex: 1;">
                            <i class="ph-bold ph-users"></i> Peserta
                        </a>
                        @if($event->status == 'Aktif')
                        <a href="{{ url('/admin/event/finish/'.$event->id) }}" onclick="return confirm('Akhiri event ini?')" class="btn btn-sm btn-warning" style="flex: 1; background: #f59e0b; color: white; border: none;">
                            <i class="ph-bold ph-stop-circle"></i> Akhiri
                        </a>
                        @endif
                    </div>
                    <div style="display: flex; gap: 8px; width: 100%;">
                        <a href="{{ url('/admin/event/edit/'.$event->id) }}" class="btn btn-sm btn-secondary" style="flex: 1;">
                            <i class="ph-bold ph-pencil-simple"></i> Edit
                        </a>
                        <a href="{{ url('/admin/event/delete/'.$event->id) }}" 
                           onclick="return confirm('Yakin mau hapus event ini?')" 
                           class="btn btn-sm btn-danger" style="flex: 1;">
                            <i class="ph-bold ph-trash"></i> Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>

@endsection