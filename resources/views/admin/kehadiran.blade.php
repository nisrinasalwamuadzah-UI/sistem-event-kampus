@extends('layouts.admin')

@section('title', 'Data Kehadiran Mahasiswa')

@section('content')

    <div class="header-section">
        <h2>Data Kehadiran Mahasiswa</h2>
        <p>Catatan riwayat absensi berdasarkan kegiatan kampus.</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Event</th>
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
                    <td>
                        <strong>{{ $item->event->nama_event ?? '-' }}</strong>
                    </td>
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
                    <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                        <i class="ph-fill ph-folder-open" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        Belum ada data kehadiran.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection