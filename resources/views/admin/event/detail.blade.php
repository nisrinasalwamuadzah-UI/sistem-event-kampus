@extends('layouts.admin')

@section('title', 'Detail Kegiatan')

@section('content')

<div style="
    background:white;
    padding:30px;
    border-radius:15px;
">

    {{-- POSTER --}}
    @if($event->poster)

        <img src="{{ $event->poster }}"
             alt="Poster"
             style="
                width:100%;
                max-height:400px;
                object-fit:cover;
                border-radius:15px;
                margin-bottom:25px;
             ">

    @endif

    {{-- NAMA --}}
    <h1 style="margin-bottom:20px;">
        {{ $event->nama_event }}
    </h1>

    {{-- DETAIL --}}
    <div style="margin-bottom:15px;">
        <strong>Tanggal:</strong>
        {{ $event->tanggal }}
    </div>

    <div style="margin-bottom:15px;">
        <strong>Lokasi:</strong>
        {{ $event->lokasi ?? '-' }}
    </div>

    <div style="margin-bottom:15px;">
        <strong>Kuota Peserta:</strong>
        {{ $event->kuota ?? '-' }}
    </div>

    <div style="margin-top:25px;">

        <strong>Deskripsi Kegiatan:</strong>

        <p style="
            margin-top:10px;
            line-height:1.8;
            color:#444;
        ">
            {{ $event->deskripsi ?? 'Belum ada deskripsi kegiatan.' }}
        </p>

    </div>

</div>

@endsection