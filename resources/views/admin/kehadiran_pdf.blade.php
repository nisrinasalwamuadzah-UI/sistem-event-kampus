<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran {{ $event->nama_event }}</title>
    <style>
        * { margin: 0; padding: 0; }
        body { 
            font-family: 'Calibri', 'Arial', sans-serif; 
            color: #000; 
            line-height: 1.5;
            font-size: 11px;
        }
        .container { max-width: 210mm; margin: 0 auto; padding: 15mm; }
        
        /* HEADER */
        .header { 
            text-align: center; 
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 { 
            font-size: 14px; 
            font-weight: bold; 
            margin-bottom: 5px;
        }
        .header p { 
            font-size: 11px; 
            margin: 3px 0;
            color: #333;
        }
        
        /* INFORMASI EVENT */
        .details {
            margin: 20px 0;
            font-size: 11px;
        }
        .details div {
            margin-bottom: 6px;
        }
        .details strong {
            display: inline-block;
            width: 120px;
            font-weight: bold;
        }
        
        /* STATISTIK */
        .summary {
            display: flex;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .summary-item {
            flex: 1;
            min-width: 150px;
            background: #f5f5f5;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .summary-item strong {
            display: block;
            margin-bottom: 8px;
            font-size: 10px;
            font-weight: bold;
        }
        .summary-item .value {
            display: block;
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }
        
        /* TABEL */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 10px;
        }
        th {
            background: #e8e8e8;
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        td {
            border: 1px solid #999;
            padding: 6px 8px;
        }
        tr:nth-child(even) { 
            background: #fafafa;
        }
        
        /* FOOTER */
        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <h1>LAPORAN KEHADIRAN</h1>
            <p>{{ $event->nama_event }}</p>
        </div>

        <!-- INFORMASI EVENT -->
        <div class="details">
            <div><strong>Tanggal Pelaksanaan</strong> : {{ $event->tanggal ? date('d F Y', strtotime($event->tanggal)) : 'Tidak diisi' }}</div>
            <div><strong>Tempat</strong> : {{ $event->tempat ?? 'Tidak diisi' }}</div>
            <div><strong>Status</strong> : {{ $event->status ?? 'Belum ditentukan' }}</div>
        </div>

        <!-- STATISTIK KEHADIRAN -->
        <div class="summary">
            <div class="summary-item">
                <strong>Total Peserta Terdaftar</strong>
                <span class="value">{{ \Illuminate\Support\Facades\DB::table('event_mahasiswa')->where('event_id', $event->id)->count() }}</span>
            </div>
            <div class="summary-item">
                <strong>Total Hadir</strong>
                <span class="value">{{ $kehadirans->count() }}</span>
            </div>
            <div class="summary-item">
                <strong>Persentase Kehadiran</strong>
                <span class="value">{{ $kehadirans->count() > 0 ? round(($kehadirans->count() / max(1, \Illuminate\Support\Facades\DB::table('event_mahasiswa')->where('event_id', $event->id)->count())) * 100) : 0 }}%</span>
            </div>
        </div>

        <!-- TABEL PESERTA -->
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 70px;">NIM</th>
                    <th>Nama</th>
                    <th style="width: 80px;">Jurusan</th>
                    <th style="width: 40px;">Sem</th>
                    <th style="width: 110px;">Waktu Scan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kehadirans as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="font-family: monospace;">{{ $item->nim }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->jurusan }}</td>
                        <td style="text-align: center;">{{ $item->semester }}</td>
                        <td style="font-size: 9px;">{{ $item->waktu_scan->format('d-m-Y H:i') }} WIB</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 18px; color: #666;">Belum ada mahasiswa yang hadir.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- FOOTER -->
        <div class="footer">
            Dicetak pada: {{ date('d-m-Y H:i:s') }} WIB
        </div>
    </div>
</body>
</html>
</body>
</html>
