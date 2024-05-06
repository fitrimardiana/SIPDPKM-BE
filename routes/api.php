<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Admin\AkunController;
use App\Http\Controllers\Api\Admin\ProposalController;
use App\Http\Controllers\Api\Admin\LPJController;
use App\Http\Controllers\Api\Admin\NotifikasiController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\JadwalController;
use App\Http\Controllers\Api\Admin\AgendaController;
use App\Http\Controllers\Api\Admin\ArsipDokumenController;
use App\Http\Controllers\Api\Admin\RiwayatController;
use App\Http\Controllers\Api\Admin\RevisiController;
use App\Http\Controllers\Api\Admin\PengunjungController;
use App\Http\Controllers\Api\Admin\DokumenSelesaiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/login', [LoginController::class, 'authenticate']);
// Route::post('/auth/reset-password', [ResetPasswordController::class, 'resetPasswordProcess']);

Route::get('/pengunjung/dashboard', [PengunjungController::class, 'dashboard']);
Route::get('/pengunjung/proposal', [PengunjungController::class, 'proposal']);
Route::get('/pengunjung/lpj', [PengunjungController::class, 'lpj']);
Route::get('/pengunjung/detail/{id}/{jenis}', [PengunjungController::class, 'detailDashboard']);
Route::get('/pengunjung/agenda', [PengunjungController::class, 'agenda']);
Route::get('/pengunjung/agenda/detail/{id}', [PengunjungController::class, 'detailAgenda']);
Route::get('/pengunjung/agenda/detail-file/{id}', [PengunjungController::class, 'detailFile']);
Route::get('/pengunjung/jadwal', [PengunjungController::class, 'jadwal']);
Route::get('/pengunjung/jadwal/detail/{id}', [PengunjungController::class, 'detailAgenda']);
Route::get('/pengunjung/jadwal/detail-file/{id}', [PengunjungController::class, 'detailFile']);
Route::get('/pengunjung/arsip', [PengunjungController::class, 'arsip']);
Route::get('/pengunjung/arsip/detail/{id}/{jenis}', [PengunjungController::class, 'detailArsip']);
Route::get('/pengunjung/arsip/detail-file/{id}', [PengunjungController::class, 'detailFileArsip']);


// lupa password
Route::post('/akun/profil/lupa-password', [AkunController::class, 'lupaPassword']);
// update passwrod
Route::post('/akun/profil/update-password', [AkunController::class, 'updatePassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/logout', [LogoutController::class, 'logout']);

    // akun
    Route::get('/akun', [AkunController::class, 'index']);
    Route::post('/akun/create', [AkunController::class, 'create']);
    Route::get('/akun/get-data/{id}', [AkunController::class, 'dataById']);
    Route::get('/akun/edit/{id}', [AkunController::class, 'edit']);
    Route::post('/akun/update', [AkunController::class, 'update']);
    Route::delete('/akun/delete/{id}', [AkunController::class, 'delete']);
    Route::post('/akun/update-process', [AkunController::class, 'update']);
    Route::get('/akun/profil', [AkunController::class, 'profil']);
    Route::post('/akun/profil/update', [AkunController::class, 'updateProfil']);

    // proposal
    Route::get('/proposal', [ProposalController::class, 'index']);
    Route::post('/proposal/step1', [ProposalController::class, 'step1']);
    Route::post('/proposal/step2/{id}', [ProposalController::class, 'step2']);
    Route::get('/proposal/prev/step3/{id}', [ProposalController::class, 'prevStep3']);
    Route::post('/proposal/step3', [ProposalController::class, 'step3']);
    Route::get('/proposal/detail/{id}', [ProposalController::class, 'detail']);
    Route::delete('/proposal/delete/{id}', [ProposalController::class, 'delete']);
    Route::post('/proposal/persetujuan-dosen/{id}', [ProposalController::class, 'dosenApproval']);
    Route::post('/proposal/persetujuan-kadep/{id}', [ProposalController::class, 'kadepApproval']);
    Route::get('/proposal/tinjau/{id}', [ProposalController::class, 'tinjau']);
    Route::get('/proposal/tinjau/edit/{id}', [ProposalController::class, 'edit']);
    Route::post('/proposal/tinjau/update', [ProposalController::class, 'update']);
    Route::post('/proposal/konfirmasi-admin', [ProposalController::class, 'adminConfirm']);

    // arsip proposal
    Route::get('/proposal/arsip', [ProposalController::class, 'arsipIndex']);
    Route::post('/proposal/arsip/update/{id}', [ProposalController::class, 'updateArsip']);

    // lpj
    Route::get('/lpj', [LPJController::class, 'index']);
    Route::get('/lpj/add', [LPJController::class, 'add']);
    Route::post('/lpj/step1', [LPJController::class, 'step1']);
    Route::get('/lpj/step2/{id}', [LPJController::class, 'step2']);
    Route::post('/lpj/step2/update/{id}', [LPJController::class, 'step2Update']);
    Route::get('/lpj/prev/step3/{id}', [LPJController::class, 'prevStep3']);
    Route::post('/lpj/step3', [LPJController::class, 'step3']);
    Route::get('/lpj/detail/{id}', [LPJController::class, 'detail']);
    Route::delete('/lpj/delete/{id}', [LPJController::class, 'delete']);
    Route::post('/lpj/persetujuan-dosen/{id}', [LPJController::class, 'dosenApproval']);
    Route::post('/lpj/persetujuan-kadep/{id}', [LPJController::class, 'kadepApproval']);
    Route::get('/lpj/tinjau/{id}', [LPJController::class, 'tinjau']);
    Route::get('/lpj/tinjau/edit/{id}', [LPJController::class, 'edit']);
    Route::post('/lpj/tinjau/update', [LPJController::class, 'update']);
    Route::post('/lpj/konfirmasi-admin', [LPJController::class, 'adminConfirm']);

    // arsip lpj
    Route::get('/lpj/arsip', [LPJController::class, 'arsipIndex']);
    Route::post('/lpj/arsip/update/{id}', [LPJController::class, 'updateArsip']);

    // notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);

    // dashboard
    // Route::get('/dashboard/count-data', [DashboardController::class, 'countData']);
    Route::get('/dashboard/all-data', [DashboardController::class, 'getAllData']);
    Route::get('/dashboard/detail/{id}/{jenis}', [DashboardController::class, 'detail']);
    Route::get('/dashboard/detail-dokumen/{id}', [DashboardController::class, 'detailDokumen']);
    Route::get('/dashboard/update', [DashboardController::class, 'update']);
    Route::get('/dashboard/konfirmasi-dosen/{id}', [DashboardController::class, 'dosenApproval']);
    Route::get('/dashboard/konfirmasi-kadep/{id}', [DashboardController::class, 'kadepApproval']);

    // jadwal
    Route::get('/jadwal', [JadwalController::class, 'index']);
    Route::get('/jadwal/add', [JadwalController::class, 'add']);
    Route::post('/jadwal/create', [JadwalController::class, 'create']);
    Route::get('/jadwal/detail/{id}', [JadwalController::class, 'detail']);
    Route::get('/jadwal/detail-proposal/{id}', [JadwalController::class, 'detailProposal']);
    Route::get('/jadwal/detail-lpj/{id}', [JadwalController::class, 'detailLPJ']);
    Route::get('/jadwal/edit/{id}', [JadwalController::class, 'edit']);
    Route::post('/jadwal/update', [JadwalController::class, 'update']);
    Route::delete('/jadwal/delete/{id}', [JadwalController::class, 'delete']);

    // agenda
    Route::get('/agenda', [AgendaController::class, 'index']);
    Route::get('/agenda/detail/{id}', [AgendaController::class, 'detail']);
    Route::get('/agenda/detail-proposal/{id}', [AgendaController::class, 'detailProposal']);
    Route::get('/agenda/detail-lpj/{id}', [AgendaController::class, 'detailLPJ']);

    // arsip
    Route::get('/arsip', [ArsipDokumenController::class, 'index']);
    Route::get('/arsip/detail/{id}/{jenis}', [ArsipDokumenController::class, 'detail']);
    Route::get('/arsip/detail-file/{id}', [ArsipDokumenController::class, 'detailFile']);
    Route::post('/arsip/update', [ArsipDokumenController::class, 'update']);
    Route::delete('/arsip/delete/{id}', [ArsipDokumenController::class, 'delete']);

    // riwayat pengajuan dokumen
    Route::get('/riwayat', [RiwayatController::class, 'index']);
    Route::get('/riwayat/detail/{id}/{jenis}', [RiwayatController::class, 'detail']);
    Route::get('/riwayat/detail-dokumen/{id}', [RiwayatController::class, 'detailDokumen']);
    Route::get('/riwayat/edit/{id}', [RiwayatController::class, 'edit']);
    Route::post('/riwayat/update', [RiwayatController::class, 'update']);

    // halaman revisi dokumen
    Route::get('/revisi', [RevisiController::class, 'index']);
    Route::get('/revisi/detail/{id}', [RevisiController::class, 'detail']);
    Route::get('/revisi/edit/{id}', [RevisiController::class, 'edit']);
    Route::post('/revisi/update', [RevisiController::class, 'update']);

    // halaman acc dosen
    Route::get('/dokumen', [DokumenSelesaiController::class, 'index']);
    Route::get('/dokumen/detail/{id}/{jenis}', [DokumenSelesaiController::class, 'detail']);
    Route::get('/dokumen/edit/{id}', [DokumenSelesaiController::class, 'edit']);
    Route::post('/dokumen/update', [DokumenSelesaiController::class, 'update']);
    Route::post('/dokumen/update-kadep', [DokumenSelesaiController::class, 'updateKadep']);
});
