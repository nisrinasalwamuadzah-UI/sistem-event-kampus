@extends('layouts.admin')

@section('title', 'Daftar Peserta Event')

@section('content')

    <div class="header-section" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2>Peserta: {{ $event->nama_event }}</h2>
            <p>Pilih mahasiswa yang diizinkan mengikuti event ini.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <a href="{{ url('/admin/event') }}" class="btn btn-secondary" style="padding: 10px 18px; font-size: 14px; border-radius: 12px;">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            <button type="button" onclick="confirmSubmit()" class="btn" style="background: linear-gradient(135deg, #0f766e, #14b8a6); color: white; padding: 10px 20px; font-size: 14px; border-radius: 12px; border: none; box-shadow: 0 6px 16px rgba(20, 184, 166, 0.24);">
                <i class="ph-bold ph-check-circle"></i> Simpan Daftar
            </button>
        </div>
    </div>

    <form action="{{ url('/admin/event/'.$event->id.'/peserta') }}" method="POST" id="pesertaForm">
        @csrf

        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 20px;">
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <label for="studentSearch" style="display: flex; align-items: center; gap: 8px; color: #334155; font-weight: 600;">
                    Cari
                </label>
                <input id="studentSearch" type="text" placeholder="Nama / NIM" style="padding: 12px 16px; border-radius: 12px; border: 1px solid #cbd5e1; width: 220px;">
                <button type="button" id="toggleViewBtn" class="btn btn-secondary" style="padding: 10px 16px; font-size: 14px; border-radius: 12px;">
                    Tampilkan semua
                </button>
            </div>
            <label for="selectAll" style="display: flex; align-items: center; gap: 8px; color: #334155; font-weight: 600;">
                <input type="checkbox" id="selectAll" style="width: 18px; height: 18px; cursor: pointer;">
                Pilih semua
            </label>
        </div>

        <div id="tableWrapper" class="table-container" style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 24px;">
            <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <tr>
                        <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600; width: 50px;">&nbsp;</th>
                        <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600;">NIM</th>
                        <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600;">Nama Mahasiswa</th>
                        <th style="padding: 16px; font-size: 13px; color: #64748b; font-weight: 600;">Jurusan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswas as $mhs)
                    <tr style="border-bottom: 1px solid #e2e8f0;" data-selected="{{ in_array($mhs->nim, $registeredNims) ? '1' : '0' }}">
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
    </form>

@endsection

@section('extra_js')
<script>
    const selectAllCheckbox = document.getElementById('selectAll');
    const searchInput = document.getElementById('studentSearch');
    const toggleViewBtn = document.getElementById('toggleViewBtn');
    let currentView = 'selected';

    function updateVisibleRows() {
        const query = searchInput.value.toLowerCase().trim();
        const rows = document.querySelectorAll('tbody tr');
        let firstVisibleRow = null;

        rows.forEach(row => {
            const nameCell = row.children[2].textContent.toLowerCase();
            const nimCell = row.children[1].textContent.toLowerCase();
            const jurusanCell = row.children[3].textContent.toLowerCase();
            const isSelected = row.getAttribute('data-selected') === '1';
            const matchesQuery = nameCell.includes(query) || nimCell.includes(query) || jurusanCell.includes(query);
            const shouldShow = (currentView === 'all' || isSelected) && matchesQuery;

            row.style.display = shouldShow ? '' : 'none';

            if (shouldShow && !firstVisibleRow) {
                firstVisibleRow = row;
            }
        });

        const visibleCheckboxes = Array.from(document.querySelectorAll('.nim-checkbox'))
            .filter(cb => cb.closest('tr').style.display !== 'none');

        selectAllCheckbox.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);
        toggleViewBtn.textContent = currentView === 'selected' ? 'Tampilkan semua' : 'Tampilkan yang sudah dipilih';

        if (firstVisibleRow) {
            document.getElementById('tableWrapper').scrollIntoView({ behavior: 'smooth', block: 'start' });
            firstVisibleRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Fitur Select All hanya untuk data yang terlihat hasil filter
    selectAllCheckbox.addEventListener('change', function(e) {
        const visibleCheckboxes = Array.from(document.querySelectorAll('.nim-checkbox'))
            .filter(cb => cb.closest('tr').style.display !== 'none');

        visibleCheckboxes.forEach(cb => cb.checked = e.target.checked);
    });

    searchInput.addEventListener('input', updateVisibleRows);

    toggleViewBtn.addEventListener('click', function() {
        currentView = currentView === 'selected' ? 'all' : 'selected';
        updateVisibleRows();
    });

    updateVisibleRows();

    // Alert Konfirmasi Ya/Tidak
    function confirmSubmit() {
        let count = document.querySelectorAll('.nim-checkbox:checked').length;
        if (confirm(`Anda telah memilih ${count} mahasiswa untuk mengikuti event ini.\nApakah Anda yakin ingin menyimpan daftar ini? (Ya / Tidak)`)) {
            document.getElementById('pesertaForm').submit();
        }
    }
</script>
@endsection
