<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KamarInapController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [KamarInapController::class, 'index'])->name('dashboard');
    Route::get('/sep-monitoring', function() {
        return view('sep-monitoring');
    })->name('sep-monitoring');
    Route::get('/klaim-kompilasi', [\App\Http\Controllers\KlaimKompilasiController::class, 'index'])->name('klaim-kompilasi');
    Route::get('/skrining-mpp', [App\Http\Controllers\SkriningMPPController::class, 'index'])->name('skrining-mpp');
    Route::post('/skrining-mpp', [App\Http\Controllers\SkriningMPPController::class, 'store'])->name('skrining-mpp.store');
    Route::put('/skrining-mpp/{id}', [App\Http\Controllers\SkriningMPPController::class, 'update'])->name('skrining-mpp.update');
    Route::delete('/skrining-mpp/{id}', [App\Http\Controllers\SkriningMPPController::class, 'destroy'])->name('skrining-mpp.destroy');
    Route::get('/pasien-rawat-inap', [App\Http\Controllers\KamarInapController::class, 'pasienRawatInap'])->name('pasien-rawat-inap');
    Route::post('/detail-status-berkas', [App\Http\Controllers\KamarInapController::class, 'detailStatusBerkas'])->name('detail-status-berkas');
    Route::get('/monitoring-klaim-bpjs', [App\Http\Controllers\MonitoringKlaimBPJSController::class, 'index'])->name('monitoring-klaim-bpjs');
    Route::get('/rawat-jalan', [App\Http\Controllers\RawatJalanController::class, 'index'])->name('rawat-jalan');
    Route::post('/rawat-jalan/detail-status-berkas', [App\Http\Controllers\RawatJalanController::class, 'detailStatusBerkas']);
});

require __DIR__.'/auth.php';
