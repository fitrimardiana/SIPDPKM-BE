<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardModel extends DB
{
    public static function countProposal()
    {
        return DB::table('laporan')->where('jenis', 'proposal')->where('arsip', '0')->count();
    }

    public static function countLpj()
    {
        return DB::table('laporan')->where('jenis', 'lpj')->where('arsip', '0')->count();
    }

    public static function countProposalMhs()
    {
        return DB::table('laporan')
            ->where('jenis', 'proposal')
            ->where('arsip', '0')
            ->where('user_id', Auth::user()->id)
            ->count();
    }

    public static function countLpjMhs()
    {
        return DB::table('laporan')
            ->where('jenis', 'lpj')
            ->where('arsip', '0')
            ->where('user_id', Auth::user()->id)
            ->count();
    }

    public static function countKegiatan()
    {
        return DB::table('kegiatan')->where('jadwal', 'yes')->count();
    }

    public static function countUser()
    {
        return DB::table('users')->count();
    }

    public static function getAllData()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('arsip', '0')
            ->where('nama_file', '!=', NULL)
            ->get();
    }

    public static function getAllDataDosen()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('arsip', '0')
            ->where('nama_file', '!=', NULL)
            // ->where('status', "Diajukan")
            ->get();
    }

    public static function getAllDataKadep()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('arsip', '0')
            ->where('nama_file', '!=', NULL)
            // ->where('status', "Disetujui Dosen")
            ->get();
    }

    public static function getAllDataMhs()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status', 'b.lpj_id', 'b.tgl_selesai')
            ->leftJoin('kegiatan as b', 'a.id', 'b.proposal_id')
            ->where('a.arsip', '0')
            ->where('a.user_id', Auth::user()->id)
            ->where('a.status', "!=", NULL)
            // ->where('status', 'Diajukan')
            ->get();
    }

    public static function getDashboardMhs()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.arsip', '0')
            ->where('a.user_id', Auth::user()->id)
            ->where('status', '!=', 'Disetujui Kadep')
            ->get();
    }

    public static function getDashboardDosen()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.arsip', '0')
            ->where('status', '!=', 'Disetujui Kadep')
            ->get();
    }

    public static function getDashboardKadep()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.arsip', '0')
            ->where('status', '!=', 'Diajukan')
            ->where('status', '!=', 'Ditolak Dosen')
            ->get();
    }

    public static function countLaporanAcc()
    {
        return DB::table('laporan')
            ->where('arsip', '0')
            ->where('status', 'Disetujui Kadep')
            ->where('user_id', Auth::user()->id)
            ->count();
    }

    public static function countRevisi()
    {
        return DB::table('laporan')
            ->where('arsip', '0')
            ->where('status', 'Revisi')
            ->where('user_id', Auth::user()->id)
            ->count();
    }

    public static function countProposalDosen()
    {
        return DB::table('laporan')
            ->where('jenis', 'proposal')
            ->where('arsip', '0')
            ->where('status', 'Diajukan')
            ->count();
    }

    public static function countProposalKadep()
    {
        return DB::table('laporan')
            ->where('jenis', 'proposal')
            ->where('arsip', '0')
            ->where('status', 'Disetujui Dosen')
            ->count();
    }

    public static function countLPJDosen()
    {
        return DB::table('laporan')
            ->where('jenis', 'lpj')
            ->where('arsip', '0')
            ->where('status', 'Diajukan')
            ->count();
    }

    public static function countLPJKadep()
    {
        return DB::table('laporan')
            ->where('jenis', 'lpj')
            ->where('arsip', '0')
            ->where('status', 'Disetujui Dosen')
            ->count();
    }

    public static function countLaporanAccDosen()
    {
        $countDosen = DB::table('laporan')
        ->where('arsip', '0')
        ->where('status', 'Disetujui Dosen')
        ->count();

    // Menghitung jumlah laporan yang disetujui oleh kadep
    $countKadep = DB::table('laporan')
        ->where('arsip', '0')
        ->where('status', 'Disetujui Kadep')
        ->count();

    // Menjumlahkan kedua hasil perhitungan
    $totalCount = $countDosen + $countKadep;

    return $totalCount;
    }

    public static function countLaporanAccKadep()
    {
        return DB::table('laporan')
            ->where('arsip', '0')
            ->where('status', 'Disetujui Kadep')
            ->count();
    }

    public static function countRevisiDosen()
    {
        return DB::table('laporan')
            ->where('arsip', '0')
            ->where('status', 'Revisi')
            ->count();
    }

    public static function getDetailProposalById($id)
    {
        return DB::table('laporan as b')
            ->select('b.nama_pj', 'b.nim_pj', 'b.jabatan', 'b.no_hp', 'a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.tempat', 'b.bidang', 'b.alur', 'b.no_kegiatan', 'b.status', 'a.tujuan', 'b.nama_file', 'a.proposal_id', 'a.lpj_id', 'a.id as kegiatan_id')
            ->leftJoin('kegiatan as a', 'b.id', 'a.proposal_id')
            ->where('b.id', $id)
            // ->where('jadwal', 'no')
            // ->where('b.arsip', '!=', '1')
            ->get();
    }

    public static function getDetailLpjById($id)
    {
        return DB::table('laporan as b')
            ->select('b.nama_pj', 'b.nim_pj', 'b.jabatan', 'b.no_hp', 'a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.tempat', 'b.bidang', "b.alur", 'b.no_kegiatan', 'b.status', 'a.tujuan', 'b.nama_file', 'a.proposal_id', 'a.lpj_id', 'a.id as kegiatan_id')
            ->leftJoin('kegiatan as a', 'b.id', 'a.lpj_id')
            ->where('b.id', $id)
            // ->where('jadwal', 'no')
            // ->where('b.arsip', '!=', '1')
            ->get();
    }

    public static function updateLaporan($id, $params)
    {
        return DB::table('laporan')->where('id', $id)->update($params);
    }
}
