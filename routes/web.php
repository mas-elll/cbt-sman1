<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LembarJawabController;
use App\Http\Controllers\LembarSoalController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\PenggunaController;
use App\Models\LembarJawab;
use App\Models\LembarSoal;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TahunAjaranController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/',function(){
    return redirect('/login');
});

Route::prefix('admin')->middleware(['auth:sanctum','role:admin'])->group(function(){
    Route::get('/',[Controller::class,'DashboardAdmin'])->name('dashboard-admin');
    // GURU
    Route::get('/guru',[PenggunaController::class,'getAllGuru'])->name('guru-admin');
    Route::post('/guru',[PenggunaController::class,'addGuru'])->name('add-guru');
    Route::put('/guru/{guru}',[PenggunaController::class,'updateGuru'])->name('update-guru');
    Route::delete('/guru/{guru}',[PenggunaController::class,'deleteGuru'])->name('delete-guru-admin');

    // SISWA
    Route::get('/siswa',[PenggunaController::class,'getAllKelas'])->name('kelas-siswa-admin');
    Route::get('/siswa/kelas/{kelas}',[PenggunaController::class,'getSiswaByKelas'])->name('siswa-by-kelas-admin');
    Route::post('/siswa',[PenggunaController::class,'addSiswa'])->name('add-siswa');
    Route::put('/siswa/{siswa}',[PenggunaController::class,'updateSiswa'])->name('update-siswa');
    Route::delete('/siswa/{siswa}',[PenggunaController::class,'deleteSiswa'])->name('delete-siswa-admin');

    // Kelas
    Route::get('/kelas', [KelasController::class, 'getAllKelas'])->name('kelas-admin');
    Route::post('/kelas', [KelasController::class, 'addKelas'])->name('add-kelas');
    Route::put('/kelas/{kelas}', [KelasController::class, 'updateKelas'])->name('update-kelas');
    Route::delete('/kelas/{kelas}', [KelasController::class, 'deleteKelas'])->name('delete-kelas');

    Route::resource('tahun_ajaran', TahunAjaranController::class);
    Route::post('/kelas', [KelasController::class, 'addKelas'])->name('add-kelas');
    Route::put('/kelas/{kelas}', [KelasController::class, 'updateKelas'])->name('update-kelas');
    Route::delete('/kelas/{kelas}', [KelasController::class, 'deleteKelas'])->name('delete-kelas');

    // Mata Pelajaran
    Route::get('/mapel', [MataPelajaranController::class, 'getAllMapel'])->name('mapel-admin');
    Route::post('/mapel', [MataPelajaranController::class, 'addMapel'])->name('add-mapel');
    Route::put('/mapel/{mapel}', [MataPelajaranController::class, 'updateMapel'])->name('update-mapel');
    Route::delete('/mapel/{mapel}', [MataPelajaranController::class, 'deleteMapel'])->name('delete-mapel');

    // lembar soal
    Route::get('/pilih-tahun-ajaran', [KelasController::class, 'pilihTahunAjaran'])->name('pilih-tahun-ajaran');
    Route::post('/set-tahun-ajaran', [KelasController::class, 'setTahunAjaran'])->name('set-tahun-ajaran1');
    Route::get('/lembar-soal',[KelasController::class,'getAllKelasByGuru'])->name('get-kelas-lembar-soal-admin');
    Route::get('/lembar-soal/guru/{guru}/kelas/{kelas}/mapel/{mapel}',[LembarSoalController::class,'getLembarSoalByKelasMapelAdmin'])->name('get-lembar-soal-by-kelas-mapel-admin');
    Route::get('/laporan/kelas/{kelas}/mapel/{mapel}/lembar-soal/{lembarSoal}',[LaporanController::class,'showPDFAdmin'])->name('show-pdf-all-siswa-kelas-mapel-admin');
    Route::get('/lembar-jawab/{lembarSoal}',[LembarJawabController::class,'AdminGetAllLembarJawabById'])->name('admin-lembar-jawab-by-id');

});

Route::prefix('guru')->middleware(['auth:sanctum','role:guru'])->group(function(){

    Route::get('/',[Controller::class,'DashboardGuru'])->name('dashboard-guru');

    // Lembar Soal
    Route::get('/lembar-soal',[LembarSoalController::class,'getLembarSoalByGuru'])->name('lembar-soal-guru');
    Route::get('/lembar-soal/mapel/{kelasId}',[LembarSoalController::class,'getMapelByGuru']);
    Route::post('/lembar-soal',[LembarSoalController::class,'addLembarSoal'])->name('add-lembar-soal');
    Route::put('/lembar-soal/{lembarSoal}',[LembarSoalController::class,'updateLembarSoal'])->name('update-lembar-soal');
    Route::delete('/lembar-soal/{lembarSoal}',[LembarSoalController::class,'deleteLembarSoal'])->name('delete-lembar-soal');
    Route::post('/lembar-soal/upload', [LembarSoalController::class, 'upload'])->name('LembarSoal-upload.upload');

    // Lembar Jawab
    Route::get('/pilih-tahun-ajaran', [LembarJawabController::class, 'pilihTahunAjaran'])->name('pilih-tahun-ajaran');
    Route::post('/set-tahun-ajaran', [LembarJawabController::class, 'setTahunAjaran'])->name('set-tahun-ajaran2');
    Route::get('/lembar-jawab',[LembarJawabController::class,'getAllKelasByGuru'])->name('get-kelas-lembar-jawab');
    Route::get('lembar-jawab/kelas/{kelas}/mapel/{mapel}',[LembarSoalController::class,'getLembarSoalByKelasMapel'])->name('get-lembar-soal-by-kelas-mapel');
    Route::get('/lembar-jawab/{lembarSoal}',[LembarJawabController::class,'getAllLembarJawabById'])->name('lembar-jawab-by-id');
    Route::put('update-nilai-jawaban/{lembarJawab}', [LembarJawabController::class, 'updateNilaiJawaban'])->name('update-nilai-jawaban');

    // Laporan
    Route::get('/laporan/kelas/{kelas}/mapel/{mapel}/lembar-soal/{lembarSoal}',[LaporanController::class,'showPDFGuru'])->name('show-pdf-all-siswa-kelas-mapel');

});
Route::prefix('siswa')->middleware(['auth:sanctum','role:siswa'])->group(function(){
    // Route::get('/')
    Route::get('/', function () {
        return redirect()->route('penugasan-tersedia-siswa');
    });
    Route::get('/tugas',[LembarSoalController::class,'getTugasSoalBySiswa'])->name('penugasan-tersedia-siswa');
    Route::get('/lembar-soal',[LembarSoalController::class,'getLembarSoalBySiswa'])->name('lembar-soal-siswa');
    Route::get('/lembar-soal/{lembarSoal}',[LembarSoalController::class,'getLembarSoalById'])->name('lembar-soal-pengerjaan');
    Route::post('/lembar-soal',[LembarJawabController::class,'SimpanLembarJawab'])->name('simpan-lembar-jawab');
    // LAPORAN
    Route::get('/pilih-tahun-ajaran2', [LembarSoalController::class, 'pilihTahunAjaran'])->name('pilih-tahun-ajaran2');
    Route::post('/set-tahun-ajaran6', [LembarSoalController::class, 'setTahunAjaran'])->name('set-tahun-ajaran6');

    Route::get('/pilih-tahun-ajaran1', [LaporanController::class, 'pilihTahunAjaran'])->name('pilih-tahun-ajaran1');
    Route::post('/set-tahun-ajaran', [LaporanController::class, 'setTahunAjaran'])->name('set-tahun-ajaran3');
    Route::get('/laporan/',[LaporanController::class,'getAllMapelByKelasSiswa'])->name('mapel-by-kelas-siswa');
    Route::get('/laporan/kelas/{kelas}/mapel/{mapel}/',[LaporanController::class,'getAllLembarJawabByMapel'])->name('laporan-lembar-jawab-by-mapel');
    Route::get('/laporan/pdf/kelas/{kelas}/mapel/{mapel}/lembarjawab/{lembarJawab}',[LaporanController::class,'showPDFSiswa'])->name('show-pdf-siswa');
    Route::get('/laporan/kelas/{kelas}/mapel/{mapel}/lembarjawab/',[LaporanController::class,'showPDFSiswa'])->name('show-all-pdf-siswa');

});
