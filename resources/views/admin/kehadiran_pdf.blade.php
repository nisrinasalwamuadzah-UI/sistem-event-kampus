<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Laporan Kehadiran - {{ $event->nama_event }}</title>
  <style>
    /* ── Reset & Base ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Times New Roman', Times, serif;
      background: #ffffff;
      padding: 0;
      margin: 0;
    }

    /* ── Kop Surat ── */
    .kop {
      width: 100%;
      border-bottom: 3px solid #0F172A;
      margin-bottom: 2px;
      padding-bottom: 10px;
    }
    
    .kop-table {
        width: 100%;
        border-collapse: collapse;
    }
    .kop-table td {
        vertical-align: middle;
    }

    .kop-logo {
      width: 80px;
      height: 80px;
    }

    .kop-text {
      text-align: center;
    }

    .kop-yayasan {
      font-size: 12pt;
      font-weight: bold;
      color: #334155;
      letter-spacing: 0.5px;
      line-height: 1.2;
    }

    .kop-nama {
      font-size: 18pt;
      font-weight: bold;
      color: #0F172A;
      letter-spacing: 1px;
      line-height: 1.2;
      margin-top: 2px;
    }

    .kop-alamat {
      font-size: 9pt;
      color: #475569;
      margin-top: 5px;
      line-height: 1.4;
    }

    /* ── Garis Pemisah Ganda ── */
    .line-tipis {
      border: none;
      border-top: 1px solid #0F172A;
      margin-bottom: 20px;
    }

    /* ── Area Isi Surat ── */
    .surat-body {
      padding: 0 10px;
      font-size: 11pt;
      color: #1e293b;
      line-height: 1.5;
    }

    .report-title {
        text-align: center;
        font-weight: bold;
        font-size: 14pt;
        margin-bottom: 20px;
        text-decoration: underline;
    }

    /* INFORMASI EVENT */
    .details {
        margin-bottom: 20px;
        font-size: 11pt;
    }
    .details table {
        width: 100%;
    }
    .details td {
        padding: 2px 0;
        vertical-align: top;
    }
    .details td.label {
        width: 150px;
        font-weight: bold;
    }

    /* STATISTIK */
    .summary {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
    }
    .summary td {
        width: 33.33%;
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
        background: #f8fafc;
    }
    .summary-label {
        font-size: 9pt;
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }
    .summary-value {
        font-size: 14pt;
        font-weight: bold;
        color: #0F172A;
    }

    /* TABEL PESERTA */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        font-size: 10pt;
    }
    .data-table th {
        background: #e2e8f0;
        border: 1px solid #94a3b8;
        padding: 8px;
        text-align: center;
        font-weight: bold;
    }
    .data-table td {
        border: 1px solid #94a3b8;
        padding: 6px 8px;
    }
    .data-table tr:nth-child(even) { 
        background: #f8fafc;
    }

    /* TTD */
    .ttd-wrapper {
      width: 100%;
      margin-top: 30px;
    }
    .ttd-box {
      width: 250px;
      float: right;
      text-align: center;
    }
    .ttd-kota-tanggal { margin-bottom: 6px; }
    .ttd-jabatan { font-weight: bold; }
    .ttd-ruang { height: 70px; }
    .ttd-nama {
      font-weight: bold;
      text-decoration: underline;
      margin-bottom: 2px;
    }
    .ttd-nip { font-size: 10pt; color: #475569; }

    /* Clearfix for float */
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }

    /* Footer */
    .footer {
        position: fixed;
        bottom: 0px;
        left: 0px;
        right: 0px;
        height: 30px;
        font-size: 8pt;
        color: #64748b;
        text-align: center;
        border-top: 1px solid #cbd5e1;
        padding-top: 5px;
    }

    @page { margin: 30px 40px 40px 40px; }
  </style>
</head>
<body>

  <!-- KOP SURAT -->
  <div class="kop">
    <table class="kop-table">
        <tr>
            <td style="width: 100px; text-align: center;">
                @php
                    $imagePath = public_path('images/logo.png');
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $src = 'data:image/png;base64,'.$imageData;
                @endphp
                <img class="kop-logo" src="{{ $src }}" alt="Logo Politeknik Baja Tegal" />
            </td>
            <td class="kop-text">
                <div class="kop-yayasan">YAYASAN PENDIDIKAN BHAKTI PRAJA TEGAL</div>
                <div class="kop-nama">POLITEKNIK BAJA TEGAL</div>
                <div class="kop-alamat">
                    Jl. Raya Slawi – Jatibarang Km. 4 Dukuhwaru, Kab. Tegal, Jawa Tengah 52472<br />
                    Telp: (0283) 6196309 / 082325580008 | Website: www.pbjt.ac.id | Email: info@pbjt.ac.id
                </div>
            </td>
        </tr>
    </table>
  </div>
  <hr class="line-tipis" />

  <!-- BODY SURAT -->
  <div class="surat-body">
      
      <div class="report-title">
          LAPORAN KEHADIRAN EVENT
      </div>

      <!-- INFORMASI EVENT -->
      <div class="details">
          <table>
              <tr>
                  <td class="label">Nama Event</td>
                  <td style="width: 10px;">:</td>
                  <td>{{ $event->nama_event }}</td>
              </tr>
              <tr>
                  <td class="label">Tanggal Pelaksanaan</td>
                  <td>:</td>
                  <td>{{ $event->tanggal ? \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y') : 'Tidak diisi' }}</td>
              </tr>
              <tr>
                  <td class="label">Tempat</td>
                  <td>:</td>
                  <td>{{ $event->tempat ?? 'Tidak diisi' }}</td>
              </tr>
          </table>
      </div>

      <!-- STATISTIK KEHADIRAN -->
      <table class="summary">
          <tr>
              <td>
                  <span class="summary-label">Total Peserta Terdaftar</span>
                  <span class="summary-value">{{ \Illuminate\Support\Facades\DB::table('event_mahasiswa')->where('event_id', $event->id)->count() }}</span>
              </td>
              <td>
                  <span class="summary-label">Total Hadir</span>
                  <span class="summary-value">{{ $kehadirans->count() }}</span>
              </td>
              <td>
                  <span class="summary-label">Persentase Kehadiran</span>
                  <span class="summary-value">{{ $kehadirans->count() > 0 ? round(($kehadirans->count() / max(1, \Illuminate\Support\Facades\DB::table('event_mahasiswa')->where('event_id', $event->id)->count())) * 100) : 0 }}%</span>
              </td>
          </tr>
      </table>

      <!-- TABEL PESERTA -->
      <table class="data-table">
          <thead>
              <tr>
                  <th style="width: 30px;">No</th>
                  <th style="width: 80px;">NIM</th>
                  <th>Nama Lengkap</th>
                  <th style="width: 120px;">Jurusan</th>
                  <th style="width: 40px;">Sem</th>
                  <th style="width: 120px;">Waktu Scan</th>
              </tr>
          </thead>
          <tbody>
              @forelse($kehadirans as $index => $item)
                  <tr>
                      <td style="text-align: center;">{{ $index + 1 }}</td>
                      <td style="text-align: center;">{{ $item->nim }}</td>
                      <td>{{ $item->nama }}</td>
                      <td style="text-align: center;">{{ $item->jurusan }}</td>
                      <td style="text-align: center;">{{ $item->semester }}</td>
                      <td style="text-align: center; font-size: 9pt;">{{ \Carbon\Carbon::parse($item->waktu_scan)->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s') }} WIB</td>
                  </tr>
              @empty
                  <tr>
                      <td colspan="6" style="text-align: center; padding: 15px;">Belum ada mahasiswa yang hadir pada event ini.</td>
                  </tr>
              @endforelse
          </tbody>
      </table>

      <!-- Tanda Tangan -->
      <div class="ttd-wrapper clearfix">
        <div class="ttd-box">
          <div class="ttd-kota-tanggal">Tegal, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
          <div class="ttd-jabatan">Ketua Panitia / Penanggung Jawab,</div>
          <div class="ttd-ruang"></div>
          <div class="ttd-nama">( ________________________ )</div>
          <div class="ttd-nip">NIP. _____________________</div>
        </div>
      </div>

  </div>

  <!-- Footer -->
  <div class="footer">
      Dokumen Laporan Kehadiran ini digenerate secara otomatis oleh Sistem Event Kampus Politeknik Baja Tegal pada {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s') }} WIB
  </div>

</body>
</html>
