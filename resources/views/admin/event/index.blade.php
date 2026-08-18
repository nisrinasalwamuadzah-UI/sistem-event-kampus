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

                <div class="event-actions d-flex flex-column gap-2 w-full">
                    <div class="d-flex gap-2 w-full justify-between">
                        <a href="{{ url('/admin/event/'.$event->id.'/peserta') }}" class="btn btn-sm btn-primary flex-1">
                            <i class="ph-bold ph-users"></i> Peserta
                        </a>
                        @if($event->status == 'Aktif')
                        <form action="{{ url('/admin/event/finish/'.$event->id) }}" method="POST" class="d-flex flex-1" onsubmit="return confirm('Akhiri event ini?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning w-full" style="background: #f59e0b; color: white; border: none;">
                                <i class="ph-bold ph-stop-circle"></i> Akhiri
                            </button>
                        </form>
                        @endif
                    </div>
                    <div class="d-flex gap-2 w-full justify-between">
                        <a href="{{ url('/admin/event/edit/'.$event->id) }}" class="btn btn-sm btn-secondary flex-1">
                            <i class="ph-bold ph-pencil-simple"></i> Edit
                        </a>
                        <form action="{{ url('/admin/event/delete/'.$event->id) }}" method="POST" class="d-flex flex-1" onsubmit="return confirm('Yakin mau hapus event ini?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger w-full">
                                <i class="ph-bold ph-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>

@endsection