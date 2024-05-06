<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RiwayatModel extends DB
{
    public static function getAllData()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.arsip', '!=', '1')
            ->where('a.user_id', Auth::user()->id)
            ->get();
    }

    public static function getDataById($id)
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.jenis', 'a.nama_file', 'a.komentar_dosen', 'a.komentar_kadep', 'a.status', 'a.bidang', 'a.no_kegiatan')
            ->where('a.arsip', '!=', '1')
            ->where('a.id', $id)
            ->get();
    }

    public static function updateLaporan($id, $params)
    {
        return DB::table('laporan')->where('id', $id)->update($params);
    }

    public static function getDetailProposalById($id)
    {
        return DB::table('laporan as b')
            ->select('b.nama_pj', 'b.nim_pj', 'b.jabatan', 'b.no_hp', 'a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.tempat', 'b.bidang', 'b.no_kegiatan', 'b.status', 'a.tujuan', 'b.nama_file', 'a.proposal_id', 'a.lpj_id')
            ->leftJoin('kegiatan as a', 'b.id', 'a.proposal_id')
            ->where('b.id', $id)
            ->where('jadwal', 'no')
            ->where('b.arsip', '!=', '1')
            ->get();
    }

    public static function getDetailLpjById($id)
    {
        return DB::table('laporan as b')
            ->select('b.nama_pj', 'b.nim_pj', 'b.jabatan', 'b.no_hp', 'a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.tempat', 'b.bidang', 'b.no_kegiatan', 'b.status', 'a.tujuan', 'b.nama_file', 'a.proposal_id', 'a.lpj_id')
            ->leftJoin('kegiatan as a', 'b.id', 'a.lpj_id')
            ->where('b.id', $id)
            ->where('jadwal', 'no')
            ->where('b.arsip', '!=', '1')
            ->get();
    }
}
