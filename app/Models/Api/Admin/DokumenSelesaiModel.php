<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DokumenSelesaiModel extends DB
{
    public static function getData()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.arsip', '!=', '1')
            ->where('a.status', 'Disetujui Dosen')
            ->get();
    }

    public static function getDataById($id)
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_file', 'a.bidang', 'a.no_kegiatan', 'a.jenis', 'a.status', 'a.komentar_dosen', 'a.komentar_kadep')
            ->where('a.id', $id)
            ->get();
    }

    public static function getDataProposalById($id)
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_pj', 'a.no_hp', 'a.jabatan', 'a.nim_pj', 'a.status', 'a.no_kegiatan', 'a.bidang', 'a.nama_file', 'b.nama as nama_kegiatan', 'b.tgl_mulai', 'b.tgl_selesai', 'b.tempat', 'b.tujuan')
            ->join('kegiatan as b', 'b.proposal_id', 'a.id')
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
            ->select('b.id', 'b.nama_pj', 'b.nim_pj', 'b.jabatan', 'b.no_hp', 'a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.tempat', 'b.bidang', 'b.no_kegiatan', 'b.status', 'a.tujuan', 'b.nama_file')
            ->leftJoin('kegiatan as a', 'b.id', 'a.proposal_id')
            ->where('b.id', $id)
            ->where('jadwal', 'no')
            ->where('b.arsip', '!=', '1')
            ->get();
    }

    public static function getDetailLpjById($id)
    {
        return DB::table('laporan as b')
            ->select('b.id', 'b.nama_pj', 'b.nim_pj', 'b.jabatan', 'b.no_hp', 'a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.tempat', 'b.bidang', 'b.no_kegiatan', 'b.status', 'a.tujuan', 'b.nama_file')
            ->leftJoin('kegiatan as a', 'b.id', 'a.lpj_id')
            ->where('b.id', $id)
            ->where('jadwal', 'no')
            ->where('b.arsip', '!=', '1')
            ->get();
    }
}
