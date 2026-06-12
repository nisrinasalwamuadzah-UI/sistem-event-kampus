@extends('layouts.admin')

@section('title', 'Data Mahasiswa')

@section('content')

    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h2>Data Mahasiswa</h2>
            <p>Database master seluruh mahasiswa terdaftar.</p>
        </div>
        
        <form method="GET" action="{{ url('/admin/mahasiswa') }}" style="display: flex; gap: 8px;">
            <select name="angkatan" class="form-control" style="width: auto; padding: 8px 36px 8px 12px; border-radius: 8px; appearance: auto; cursor: pointer;">
                <option value="">Semua Angkatan</option>
                @foreach($angkatans as $thn)
                    <option value="{{ $thn }}" {{ $angkatan == $thn ? 'selected' : '' }}>Angkatan {{ $thn }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary" style="padding: 8px 16px;">
                <i class="ph-bold ph-funnel"></i> Filter
            </button>
        </form>
    </div>

    <div class="table-container" style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 24px;">
        <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <tr>
                    <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600;">No</th>
                    <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600;">NIM</th>
                    <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600;">Nama Lengkap</th>
                    <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600;">Jurusan</th>
                    <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600;">Semester</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mahasiswas as $index => $mhs)
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 16px; color: #64748b;">{{ $index + 1 }}</td>
                    <td style="padding: 16px; font-weight: 600; color: #0f172a; font-family: monospace; font-size: 15px;">{{ $mhs->nim }}</td>
                    <td style="padding: 16px; color: #334155; font-weight: 500;">{{ $mhs->nama }}</td>
                    <td style="padding: 16px; color: #64748b;">{{ $mhs->jurusan }}</td>
                    <td style="padding: 16px;"><span class="badge" style="background: #eef2ff; color: #4f46e5; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">{{ $mhs->semester }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 30px; text-align: center; color: #94a3b8;">
                        <i class="ph-fill ph-users" style="font-size: 48px; margin-bottom: 12px; color: #cbd5e1;"></i><br>
                        Tidak ada data mahasiswa ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
