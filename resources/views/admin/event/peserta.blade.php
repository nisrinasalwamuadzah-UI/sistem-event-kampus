@extends('layouts.admin')

@section('title', 'Daftar Peserta Event')

@section('content')

    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>Peserta: {{ $event->nama_event }}</h2>
            <p>Pilih mahasiswa yang diizinkan mengikuti event ini.</p>
        </div>
        <a href="{{ url('/admin/event') }}" class="btn btn-secondary">
            <i class="ph-bold ph-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ url('/admin/event/'.$event->id.'/peserta') }}" method="POST" id="pesertaForm">
        @csrf

        <div class="table-container" style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 24px;">
            <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <tr>
                        <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600; width: 50px;">
                            <input type="checkbox" id="selectAll" style="width: 18px; height: 18px; cursor: pointer;">
                        </th>
                        <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600;">NIM</th>
                        <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600;">Nama Mahasiswa</th>
                        <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600;">Jurusan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswas as $mhs)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 16px;">
                            <input type="checkbox" name="nims[]" value="{{ $mhs->nim }}" class="nim-checkbox" style="width: 18px; height: 18px; cursor: pointer;"
                                {{ in_array($mhs->nim, $registeredNims) ? 'checked' : '' }}>
                        </td>
                        <td style="padding: 16px; font-weight: 600; color: #0f172a; font-family: monospace; font-size: 15px;">{{ $mhs->nim }}</td>
                        <td style="padding: 16px; color: #334155; font-weight: 500;">{{ $mhs->nama }}</td>
                        <td style="padding: 16px; color: #64748b;">{{ $mhs->jurusan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="button" onclick="confirmSubmit()" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 16px; border-radius: 12px;">
            <i class="ph-bold ph-check-circle"></i> Selesai (Simpan Daftar Peserta)
        </button>
    </form>

@endsection

@section('extra_js')
<script>
    // Fitur Select All
    document.getElementById('selectAll').addEventListener('change', function(e) {
        let checkboxes = document.querySelectorAll('.nim-checkbox');
        checkboxes.forEach(cb => cb.checked = e.target.checked);
    });

    // Alert Konfirmasi Ya/Tidak
    function confirmSubmit() {
        let count = document.querySelectorAll('.nim-checkbox:checked').length;
        if (confirm(`Anda telah memilih ${count} mahasiswa untuk mengikuti event ini.\nApakah Anda yakin ingin menyimpan daftar ini? (Ya / Tidak)`)) {
            document.getElementById('pesertaForm').submit();
        }
    }
</script>
@endsection
