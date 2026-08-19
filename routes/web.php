<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\EventController;
use App\Http\Controllers\KehadiranController;

use App\Http\Controllers\AuthController;

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

Route::get('/admin/login', [AuthController::class, 'showAdminLogin']);
Route::post('/admin/login', [AuthController::class, 'processAdminLogin']);

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PimpinanDashboardController;

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| ROUTE GROUP: ADMIN ONLY (SECURITY FIX)
|--------------------------------------------------------------------------
*/
Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {

    /* --- EVENT CRUD --- */
    Route::get('/admin/event', [EventController::class, 'index']);
    Route::get('/admin/event/create', [EventController::class, 'create']);
    Route::post('/admin/event/store', [EventController::class, 'store']);
    
    Route::get('/admin/event/edit/{id}', [EventController::class, 'edit']);
    Route::put('/admin/event/update/{id}', [EventController::class, 'update']);
    Route::delete('/admin/event/delete/{id}', [EventController::class, 'delete']);
    Route::get('/admin/event/{id}/peserta', [EventController::class, 'peserta']);
    Route::post('/admin/event/{id}/peserta', [EventController::class, 'updatePeserta']);
    Route::post('/admin/event/finish/{id}', [EventController::class, 'finish']);

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
    
    Route::get('/admin/kehadiran', [KehadiranController::class, 'index']);
    Route::get('/admin/kehadiran/{id}/export-pdf', [KehadiranController::class, 'exportPdf']);
    Route::get('/admin/kehadiran/{id}/save-pdf', [KehadiranController::class, 'savePdf']);
    Route::get('/admin/kehadiran/{id}', [KehadiranController::class, 'show']);

}); // End of Admin Group

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::get('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| PIMPINAN LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/pimpinan/login', [AuthController::class, 'showPimpinanLogin']);
Route::post('/pimpinan/login', [AuthController::class, 'processPimpinanLogin']);

/*
|--------------------------------------------------------------------------
| ROUTE GROUP: PIMPINAN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware('pimpinan')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PIMPINAN DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/pimpinan/dashboard', [PimpinanDashboardController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | TOMBOL ACC & EXPORT
    |--------------------------------------------------------------------------
    */
    Route::post('/pimpinan/event/finish/{id}', [EventController::class, 'finish']);

    Route::get('/pimpinan/export/kehadiran/{event_id?}', [KehadiranController::class, 'exportPimpinan']);
    Route::get('/pimpinan/kehadiran/{id}/export-pdf', [KehadiranController::class, 'exportPdf']);

});