@extends('layouts.admin')

@section('title', 'Tambah Event')

@section('content')

    <div class="header-section">
        <h2>Tambah Event Kegiatan</h2>
        <p>Silakan isi detail kegiatan baru di bawah ini.</p>
    </div>

    <div class="form-container">
        @if ($errors->any())
            <div class="alert alert-danger" style="background-color: #ffcccc; color: #cc0000; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/admin/event/store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Event</label>
                <input type="text" name="nama_event" class="form-control" required placeholder="Contoh: Seminar Nasional Teknologi">
            </div>

            <div class="form-group">
                <label>Tanggal Event</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Tempat Event</label>
                <input type="text" name="tempat" class="form-control" placeholder="Contoh: Aula Gedung B">
            </div>

            <div class="form-group">
                <label>Deskripsi Event</label>
                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Deskripsi singkat kegiatan..."></textarea>
            </div>

            <div class="form-group">
                <label>Poster Event</label>
                <input type="file" name="poster" class="form-control" required style="padding: 9px 16px;">
            </div>

            <div class="d-flex flex-wrap gap-2" style="margin-top: 30px;">
                <button type="submit" class="btn btn-primary flex-1 d-flex justify-center">
                    <i class="ph-bold ph-floppy-disk"></i> Simpan Event
                </button>
                <a href="{{ url('/admin/event') }}" class="btn btn-secondary flex-1 d-flex justify-center">Batal</a>
            </div>
        </form>
    </div>

@endsection