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
| ROUTE GROUP: ADMIN ONLY (SECURITY FIX)
|--------------------------------------------------------------------------
*/
Route::middleware(function ($request, $next) {
    if (Session::get('role') != 'admin') {
        return redirect('/admin/login');
    }
    return $next($request);
})->group(function () {

    /* --- EVENT CRUD --- */
    Route::get('/admin/event', [EventController::class, 'index']);
    Route::get('/admin/event/create', [EventController::class, 'create']);
    Route::post('/admin/event/store', [EventController::class, 'store']);
    Route::get('/admin/event/edit/{id}', [EventController::class, 'edit']);
    Route::post('/admin/event/update/{id}', [EventController::class, 'update']);
    Route::post('/admin/event/delete/{id}', [EventController::class, 'delete']);
    Route::get('/admin/event/{id}/peserta', [EventController::class, 'peserta']);
    Route::post('/admin/event/{id}/peserta', [EventController::class, 'updatePeserta']);
    Route::post('/admin/event/finish/{id}', function ($id) {
        $event = \App\Models\Event::findOrFail($id);
        $event->status = 'Selesai';
        $event->save();
        return back()->with('success', 'Event berhasil diakhiri.');
    });

    /* --- MAHASISWA --- */
    Route::get('/admin/mahasiswa', [App\Http\Controllers\MahasiswaController::class, 'index']);
    Route::post('/admin/mahasiswa/import', [App\Http\Controllers\MahasiswaController::class, 'import']);
    Route::get('/admin/mahasiswa/template', [App\Http\Controllers\MahasiswaController::class, 'downloadTemplate']);

    /* --- SCAN & KEHADIRAN --- */
    Route::get('/scan', function () {
        return view('admin.scan');
    });
    Route::get('/admin/scan', [KehadiranController::class, 'scan']);
    Route::post('/admin/scan', [KehadiranController::class, 'store']);
    
    Route::get('/admin/kehadiran', function () {
        $events = \App\Models\Event::withCount('kehadirans')->latest()->get();
        return view('admin.kehadiran', compact('events'));
    });
    Route::get('/admin/kehadiran/{id}/export-pdf', [KehadiranController::class, 'exportPdf']);
    Route::get('/admin/kehadiran/{id}/save-pdf', [KehadiranController::class, 'savePdf']);
    Route::get('/admin/kehadiran/{id}', function ($id) {
        $event = \App\Models\Event::findOrFail($id);
        $kehadirans = $event->kehadirans()->latest()->get();
        $totalRegistered = \Illuminate\Support\Facades\DB::table('event_mahasiswa')->where('event_id', $id)->count();
        $presentCount = $kehadirans->count();
        $absentCount = max($totalRegistered - $presentCount, 0);
        return view('admin.kehadiran', compact('event', 'kehadirans', 'totalRegistered', 'presentCount', 'absentCount'));
    });

}); // End of Admin Group

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