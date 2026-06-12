<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\EventController;
use App\Http\Controllers\KehadiranController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
});

/*
|--------------------------------------------------------------------------
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', function () {
    return view('admin.login');
});

Route::post('/admin/login', function (Request $request) {

    if ($request->username == 'admin' && $request->password == '123') {

        Session::put('role', 'admin');

        return redirect('/admin/dashboard');
    }

    return back()->with('error', 'Login gagal');
});

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', function () {

    if (Session::get('role') != 'admin') {
        return redirect('/admin/login');
    }

    $events = \App\Models\Event::latest()->get();

    $mahasiswas = \App\Models\Mahasiswa::all();

    $totalEvent = \App\Models\Event::count();

    $totalHadir = \App\Models\Kehadiran::count();

    $totalTidakHadir = 0;

    return view('admin.dashboard', compact(
        'events',
        'mahasiswas',
        'totalEvent',
        'totalHadir',
        'totalTidakHadir'
    ));
});
/*
|--------------------------------------------------------------------------
| EVENT (CRUD)
|--------------------------------------------------------------------------
*/

Route::get('/admin/event', [EventController::class, 'index']);

Route::get('/admin/event/create', [EventController::class, 'create']);

Route::post('/admin/event/store', [EventController::class, 'store']);

Route::get('/admin/event/edit/{id}', [EventController::class, 'edit']);

Route::post('/admin/event/update/{id}', [EventController::class, 'update']);

Route::get('/admin/event/delete/{id}', [EventController::class, 'delete']);

Route::get('/admin/event/{id}/peserta', [EventController::class, 'peserta']);
Route::post('/admin/event/{id}/peserta', [EventController::class, 'updatePeserta']);

/*
|--------------------------------------------------------------------------
| MAHASISWA
|--------------------------------------------------------------------------
*/

Route::get('/admin/mahasiswa', [App\Http\Controllers\MahasiswaController::class, 'index']);

/*
|--------------------------------------------------------------------------
| PIMPINAN LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/pimpinan/login', function () {
    return view('pimpinan.login');
});

Route::post('/pimpinan/login', function (Request $request) {

    if ($request->username == 'pimpinan' && $request->password == '123') {

        Session::put('role', 'pimpinan');

        return redirect('/pimpinan/dashboard');
    }

    return back()->with('error', 'Login gagal');
});

/*
|--------------------------------------------------------------------------
| PIMPINAN DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/pimpinan/dashboard', function () {

    if (Session::get('role') != 'pimpinan') {
        return redirect('/pimpinan/login');
    }

    return view('pimpinan.dashboard');
});

/*
|--------------------------------------------------------------------------
| TOMBOL ACC & EXPORT
|--------------------------------------------------------------------------
*/

Route::get('/pimpinan/event/finish/{id}', function ($id) {
    $event = \App\Models\Event::find($id);
    $event->status = 'Selesai';
    $event->save();
    return redirect('/pimpinan/dashboard');
});

Route::get('/pimpinan/export/kehadiran/{event_id?}', [KehadiranController::class, 'exportPimpinan']);

/*
|--------------------------------------------------------------------------
| SCAN ABSENSI LAMA
|--------------------------------------------------------------------------
*/

Route::get('/scan', function () {

    if (Session::get('role') != 'admin') {
        return redirect('/admin/login');
    }

    return view('admin.scan');
});

/*
|--------------------------------------------------------------------------
| KEHADIRAN
|--------------------------------------------------------------------------
*/

Route::get('/admin/kehadiran', function () {

    if (Session::get('role') != 'admin') {
        return redirect('/admin/login');
    }

    $kehadirans = \App\Models\Kehadiran::latest()->get();

    return view('admin.kehadiran', compact('kehadirans'));
});
/*
|--------------------------------------------------------------------------
| SCAN ABSENSI BARU
|--------------------------------------------------------------------------
*/

Route::get('/admin/scan', [KehadiranController::class, 'scan']);

Route::post('/admin/scan', [KehadiranController::class, 'store']);

/*
|--------------------------------------------------------------------------
| EVENT REGISTRATION (REMOVED)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::get('/logout', function () {

    Session::forget('role');

    return redirect('/');
});