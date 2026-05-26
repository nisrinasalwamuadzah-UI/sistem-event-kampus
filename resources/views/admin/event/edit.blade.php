@extends('layouts.admin')

@section('title', 'Edit Event')

@section('content')

    <div class="header-section">
        <h2>Edit Event</h2>
        <p>Perbarui informasi kegiatan kampus di bawah ini.</p>
    </div>

    <div class="form-container">
        <form action="{{ url('/admin/event/update/'.$event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Event</label>
                <input type="text" name="nama_event" class="form-control" value="{{ $event->nama_event }}" required>
            </div>

            <div class="form-group">
                <label>Tanggal Event</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $event->tanggal }}" required>
            </div>

            <div class="form-group">
                <label>Tempat Event</label>
                <input type="text" name="tempat" class="form-control" value="{{ $event->tempat }}">
            </div>

            <div class="form-group">
                <label>Deskripsi Event</label>
                <textarea name="deskripsi" class="form-control" rows="4">{{ $event->deskripsi }}</textarea>
            </div>

            <div class="form-group">
                <label>Poster Lama</label>
                <div style="margin-top: 10px; margin-bottom: 20px;">
                    <img src="{{ asset('storage/'.$event->poster) }}" style="width: 200px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                </div>
            </div>

            <div class="form-group">
                <label>Ganti Poster (Opsional)</label>
                <input type="file" name="poster" class="form-control" style="padding: 9px 16px;">
            </div>

            <div style="margin-top: 30px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="ph-bold ph-floppy-disk"></i> Update Event
                </button>
                <a href="{{ url('/admin/event') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

@endsection