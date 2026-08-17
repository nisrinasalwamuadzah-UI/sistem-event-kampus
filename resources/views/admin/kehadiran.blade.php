@extends('layouts.admin')

@section('title', isset($event) ? 'Daftar Kehadiran Event' : 'Data Kehadiran Mahasiswa')

@section('content')

    @if(isset($event))
        <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div>
                <h2>Daftar Kehadiran: {{ $event->nama_event }}</h2>
                <p>{{ $event->tanggal }} · {{ $event->tempat ?? 'Lokasi belum diisi' }}</p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: flex-end;">
                <a href="{{ url('/admin/kehadiran/'.$event->id.'/save-pdf') }}" class="btn btn-save" style="white-space: nowrap;">
                    <i class="ph-bold ph-floppy-disk"></i> Simpan PDF
                </a>
                <a href="{{ url('/admin/kehadiran/'.$event->id.'/export-pdf') }}" class="btn btn-export" style="white-space: nowrap;">
                    <i class="ph-bold ph-download"></i> Export PDF
                </a>
                <a href="{{ url('/admin/kehadiran') }}" class="btn btn-back" style="white-space: nowrap;">
                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Event
                </a>
            </div>
        </div>

        @if(session('pdf_path'))
            <div class="alert alert-success" style="margin-bottom: 24px; padding: 18px; border-radius: 12px; background: #e6fffa; color: #065f46; border: 1px solid #bde8dd;">
                Laporan telah disimpan. <a href="{{ session('pdf_path') }}" target="_blank" style="font-weight: 600; color: #0f766e;">Buka PDF</a>
            </div>
        @endif

        <div class="cards" style="margin-bottom: 32px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; display: grid;">
            <div class="card blue" style="padding: 20px;">
                <div class="card-icon blue"><i class="ph-fill ph-check-circle"></i></div>
                <div class="card-info">
                    <h3>Total Terdaftar</h3>
                    <p>{{ $totalRegistered }}</p>
                </div>
            </div>
            <div class="card green" style="padding: 20px;">
                <div class="card-icon green"><i class="ph-fill ph-user-circle"></i></div>
                <div class="card-info">
                    <h3>Total Hadir</h3>
                    <p>{{ $presentCount }}</p>
                </div>
            </div>
            <div class="card orange" style="padding: 20px;">
                <div class="card-icon orange"><i class="ph-fill ph-chart-pie"></i></div>
                <div class="card-info">
                    <h3>Persentase Kehadiran</h3>
                    <p>{{ $totalRegistered > 0 ? round($presentCount / $totalRegistered * 100) : 0 }}%</p>
                </div>
            </div>
        </div>

        <div style="background: white; border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.03); padding: 32px; margin-bottom: 32px; display: flex; flex-direction: column; align-items: center;">
            <h3 style="margin-bottom: 24px; color: #0f172a; font-size: 18px; font-weight: 600;">Diagram Kehadiran</h3>
            <div style="width: 100%; max-width: 400px; height: 280px; position: relative;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <div class="table-container" style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow-x: auto; margin-bottom: 24px;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 600px;">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Semester</th>
                        <th>Waktu Scan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kehadirans as $item)
                    <tr>
                        <td><span style="font-family: monospace; background: #f1f5f9; padding: 4px 8px; border-radius: 6px;">{{ $item->nim }}</span></td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->jurusan }}</td>
                        <td><span class="badge" style="background: #eef2ff; color: #4f46e5;">{{ $item->semester }}</span></td>
                        <td style="color: #64748b; font-size: 13px;">
                            <i class="ph-bold ph-clock" style="margin-right: 4px; vertical-align: middle;"></i>
                            {{ $item->waktu_scan->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
                            <i class="ph-fill ph-folder-open" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                            Belum ada mahasiswa yang hadir di event ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="header-section">
            <h2>Data Kehadiran Berdasarkan Event</h2>
            <p>Pilih event untuk melihat daftar mahasiswa hadir di acara tersebut.</p>
        </div>

        <div class="event-grid">
            @forelse($events as $eventItem)
                <div class="event-card">
                    <img src="{{ asset('storage/'.$eventItem->poster) }}" alt="Poster {{ $eventItem->nama_event }}">
                    <div class="event-content">
                        <div class="event-title">{{ $eventItem->nama_event }}</div>
                        <div class="event-info">
                            <i class="ph-bold ph-calendar-blank"></i>
                            <span>{{ $eventItem->tanggal }}</span>
                        </div>
                        <div class="event-info">
                            <i class="ph-bold ph-map-pin"></i>
                            <span>{{ $eventItem->tempat ?? 'Lokasi belum diisi' }}</span>
                        </div>
                        <div class="event-desc" style="margin-top: 12px;">{{ $eventItem->kehadirans_count }} mahasiswa hadir</div>
                    </div>
                    <div style="padding: 16px; display: flex; justify-content: flex-end; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                        <a href="{{ url('/admin/kehadiran/'.$eventItem->id) }}" class="btn btn-sm btn-primary" style="width: 100%;">
                            <i class="ph-bold ph-users"></i> Lihat Daftar
                        </a>
                    </div>
                </div>
            @empty
                <div class="table-container" style="padding: 40px; text-align: center; color: #94a3b8;">
                    <i class="ph-fill ph-folder-open" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                    Belum ada event untuk ditampilkan.
                </div>
            @endforelse
        </div>
    @endif

@endsection

@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const presentCount = {{ $presentCount ?? 0 }};
    const absentCount = {{ $absentCount ?? 0 }};
    const ctx = document.getElementById('attendanceChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Tidak Hadir'],
                datasets: [{
                    data: [presentCount, absentCount],
                    backgroundColor: ['#10B981', '#cbd5e1'], /* Emerald for Present, Light Slate for Absent */
                    borderColor: ['#ffffff', '#ffffff'],
                    borderWidth: 4, /* Thicker border for a modern donut slice separation */
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%', /* Thinner donut ring */
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = presentCount + absentCount;
                                const percentage = total ? Math.round((value / total) * 100) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endsection