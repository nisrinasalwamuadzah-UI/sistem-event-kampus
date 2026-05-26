@extends('layouts.admin')

@section('title', 'Dashboard Pimpinan')

@section('content')

    <div class="header-section" style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h2>Dashboard Pimpinan</h2>
            <p>Monitoring kegiatan dan kehadiran mahasiswa</p>
        </div>
        <a href="{{ url('/pimpinan/export/kehadiran') }}" class="btn btn-primary" style="background: #10b981; color: white;">
            <i class="ph-bold ph-download-simple"></i> Unduh Semua Kehadiran
        </a>
    </div>

    <!-- STATISTIK -->
    <div class="cards">
        <div class="card">
            <div class="card-icon indigo"><i class="ph-fill ph-calendar-check"></i></div>
            <div class="card-info">
                <h3>Total Event</h3>
                <p>{{ \App\Models\Event::count() }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-icon green"><i class="ph-fill ph-check-circle"></i></div>
            <div class="card-info">
                <h3>Total Kehadiran</h3>
                <p>{{ \App\Models\Kehadiran::count() }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-icon blue"><i class="ph-fill ph-users"></i></div>
            <div class="card-info">
                <h3>Total Mahasiswa</h3>
                <p>{{ \App\Models\Mahasiswa::count() }}</p>
            </div>
        </div>
    </div>

    <!-- EVENT -->
    <div class="section-title">
        <span>Kegiatan Kampus</span>
    </div>

    <div class="event-grid">
        @foreach(\App\Models\Event::latest()->get() as $event)
        <div class="event-card">
            <img src="{{ asset('storage/'.$event->poster) }}">
            
            <div class="event-content">
                <div class="event-title">
                    {{ $event->nama_event }}
                </div>

                <div class="event-info">
                    <i class="ph-bold ph-calendar-blank"></i>
                    <span>{{ $event->tanggal }}</span>
                </div>

                <div class="event-info">
                    <i class="ph-bold ph-map-pin"></i>
                    <span>{{ $event->tempat }}</span>
                </div>

                <div class="event-actions">
                    @if($event->status == 'Aktif')
                        <span class="badge aktif">Aktif</span>
                        <a href="{{ url('/pimpinan/event/finish/'.$event->id) }}" 
                           onclick="return confirm('Apakah Anda yakin ingin mengakhiri event ini? Absensi akan ditutup.')" 
                           class="btn btn-sm btn-danger" style="margin-left: auto;">
                            <i class="ph-bold ph-stop-circle"></i> Akhiri Event
                        </a>
                    @else
                        <span class="badge selesai">Telah Selesai</span>
                        <a href="{{ url('/pimpinan/export/kehadiran/'.$event->id) }}" class="btn btn-sm btn-secondary" style="margin-left: auto; border-color: #10b981; color: #10b981;">
                            <i class="ph-bold ph-file-csv"></i> Unduh Data
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

@endsection