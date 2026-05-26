<!DOCTYPE html>
<html>
<head>
    <title>Daftar Hadir</title>
</head>
<body>

<h2>DAFTAR HADIR MAHASISWA</h2>

<table border="1" cellpadding="10">

    <tr>
        <th>NIM</th>
        <th>Nama</th>
        <th>Jurusan</th>
        <th>Semester</th>
        <th>Waktu Scan</th>
    </tr>

    @foreach($data as $d)
    <tr>
        <td>{{ $d->nim }}</td>
        <td>{{ $d->nama }}</td>
        <td>{{ $d->jurusan }}</td>
        <td>{{ $d->semester }}</td>
        <td>{{ $d->waktu_scan }}</td>
    </tr>
    @endforeach

</table>

</body>
</html>