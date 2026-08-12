@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

    <div class="header-section">
        <h2>Dashboard Kegiatan Kampus PBJT</h2>
        <p>Sistem Informasi Kegiatan Kampus</p>
    </div>

    <!-- STATISTIK -->
    <div class="cards">
        <div class="card blue">
            <div class="card-icon blue"><i class="ph-fill ph-users"></i></div>
            <div class="card-info">
                <h3>Total Mahasiswa Hadir</h3>
                <p>{{ $totalHadir }}</p>
            </div>
        </div>

        <div class="card red">
            <div class="card-icon red"><i class="ph-fill ph-user-minus"></i></div>
            <div class="card-info">
                <h3>Total Tidak Hadir</h3>
                <p>{{ $totalTidakHadir }}</p>
            </div>
        </div>

        <div class="card green">
            <div class="card-icon green"><i class="ph-fill ph-calendar-check"></i></div>
            <div class="card-info">
                <h3>Total Event</h3>
                <p>{{ $totalEvent }}</p>
            </div>
        </div>
    </div>

    <!-- EVENT -->
    <div class="section-title">
        <span>Informasi Kegiatan Kampus</span>
        <a href="{{ url('/admin/event') }}" class="btn btn-sm btn-secondary">
            Lihat Semua <i class="ph-bold ph-arrow-right"></i>
        </a>
    </div>

    <div class="event-grid">
        @foreach($events as $event)
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
                    <span>{{ $event->tempat ?? 'Aula Kampus' }}</span>
                </div>

                <div class="event-desc">
                    {{ Str::limit($event->deskripsi ?? 'Tidak ada deskripsi kegiatan.', 100) }}
                </div>
            </div>
            
            <div style="padding: 15px; border-top: 1px solid #e2e8f0; text-align: center;">
                <span class="badge {{ strtolower($event->status) == 'aktif' ? 'aktif' : 'selesai' }}">
                    {{ $event->status }}
                </span>
            </div>
        </div>
        @endforeach
    </div>

@endsection