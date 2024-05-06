<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RevisiModel extends DB
{
    public static function getData()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.arsip', '!=', '1')
            ->where('a.user_id', Auth::user()->id)
            ->where('a.status', 'Revisi')
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
}
