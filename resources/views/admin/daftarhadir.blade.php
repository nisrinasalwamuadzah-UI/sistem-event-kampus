@extends('admin.layout')

@section('content')

<h2>DAFTAR HADIR</h2>

<br>

<table border="1" cellpadding="15" width="100%">

    <tr>
        <th>NIM</th>
        <th>Status</th>
    </tr>

    <tr>
        <td>{{ $nim }}</td>
        <td>Hadir</td>
    </tr>

</table>

@endsection