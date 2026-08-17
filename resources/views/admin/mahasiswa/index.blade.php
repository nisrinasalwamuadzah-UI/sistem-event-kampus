@extends('layouts.admin')

@section('title', 'Data Mahasiswa')

@section('content')

    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <h2>Data Mahasiswa</h2>
            <p>Kelola seluruh data mahasiswa yang terdaftar di sistem.</p>
        </div>
        
        <div style="display: flex; gap: 8px;">
            <form method="GET" action="{{ url('/admin/mahasiswa') }}" style="display: flex; gap: 8px; flex-wrap: wrap;">
                <select name="event_id" class="form-control" style="flex: 1 1 200px; max-width: 100%; padding: 8px 40px 8px 12px; border-radius: 8px; cursor: pointer;">
                    <option value="">Semua Event</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ ($eventId ?? '') == $event->id ? 'selected' : '' }}>{{ $event->nama_event }}</option>
                    @endforeach
                </select>
                <select name="angkatan" class="form-control" style="flex: 1 1 170px; max-width: 100%; padding: 8px 40px 8px 12px; border-radius: 8px; cursor: pointer;">
                    <option value="">Semua Angkatan</option>
                    @foreach($angkatans as $thn)
                        <option value="{{ $thn }}" {{ $angkatan == $thn ? 'selected' : '' }}>Angkatan {{ $thn }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary" style="padding: 8px 16px; flex: 1 1 auto; white-space: nowrap;">
                    <i class="ph-bold ph-funnel"></i> Filter
                </button>
            </form>

            <button type="button" class="btn btn-primary" onclick="document.getElementById('importModal').style.display='flex'" style="padding: 8px 16px; border-radius: 8px;">
                <i class="ph-bold ph-file-xls"></i> Import Data
            </button>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #059669; padding: 16px; border-radius: 12px; border: 1px solid #a7f3d0; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
            <i class="ph-bold ph-check-circle" style="font-size: 20px;"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; color: #dc2626; padding: 16px; border-radius: 12px; border: 1px solid #fecaca; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
            <i class="ph-bold ph-warning-circle" style="font-size: 20px;"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fef2f2; color: #dc2626; padding: 16px; border-radius: 12px; border: 1px solid #fecaca; margin-bottom: 24px; font-size: 14px;">
            <ul style="margin-left: 15px; margin-bottom: 0;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-container" style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow-x: auto; margin-bottom: 24px;">
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

    <div id="importModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 16px;">
        <div style="background: white; width: 100%; max-width: 500px; border-radius: 20px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 18px; color: #0f172a;"><i class="ph-bold ph-upload-simple"></i> Import Data Mahasiswa</h3>
                <button type="button" onclick="document.getElementById('importModal').style.display='none'" style="background: transparent; border: none; font-size: 20px; cursor: pointer; color: #64748b;">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/mahasiswa/import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 32px 20px; text-align: center; margin-bottom: 16px;">
                    <i class="ph-bold ph-file-csv" style="font-size: 48px; color: #94a3b8; margin-bottom: 12px;"></i>
                    <p style="color: #475569; font-size: 14px; margin-bottom: 12px;">Pilih file berformat .xlsx, .xls, atau .csv</p>
                    <input type="file" name="csv_file" accept=".csv,.txt,.xlsx,.xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" required style="max-width: 100%; font-size: 14px;">
                </div>

                <div style="background: #eff6ff; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; color: #1e3a8a;">
                    <i class="ph-bold ph-info"></i> Pastikan format baris pertama sesuai dengan Template. Jika ragu, unduh template di bawah.
                </div>

                <div style="display: flex; gap: 12px; flex-wrap: wrap; justify-content: flex-end;">
                    <a href="{{ url('/admin/mahasiswa/template') }}" class="btn btn-secondary" style="font-size: 14px; padding: 10px 16px;">
                        Unduh Template
                    </a>
                    <button type="submit" class="btn btn-primary" style="font-size: 14px; padding: 10px 24px;">
                        Upload & Proses
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
