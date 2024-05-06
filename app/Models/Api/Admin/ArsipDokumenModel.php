<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArsipDokumenModel extends DB
{
    public static function getData()
    {
        return DB::table('laporan')->where('arsip', '1')->get();
    }

    public static function getDetailById($id)
    {
        return DB::table('laporan')->where('id', $id)->first();
    }

    public static function getDetailProposalById($id)
    {
        return DB::table('laporan as b')
            ->select('b.nama_pj', 'b.id', 'b.nim_pj', 'b.jabatan', 'b.no_hp', 'a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.tempat', 'b.bidang', 'b.no_kegiatan', 'b.status', 'a.tujuan', 'b.nama_file', 'a.proposal_id', 'a.lpj_id')
            ->leftJoin('kegiatan as a', 'b.id', 'a.proposal_id')
            ->where('b.id', $id)
            ->where('jadwal', 'no')
            ->where('b.arsip', '=', '1')
            ->get();
    }

    public static function getDetailLpjById($id)
    {
        return DB::table('laporan as b')
            ->select('b.nama_pj', 'b.id', 'b.nim_pj', 'b.jabatan', 'b.no_hp', 'a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.tempat', 'b.bidang', 'b.no_kegiatan', 'b.status', 'a.tujuan', 'b.nama_file', 'a.proposal_id', 'a.lpj_id')
            ->leftJoin('kegiatan as a', 'b.id', 'a.lpj_id')
            ->where('b.id', $id)
            ->where('jadwal', 'no')
            ->where('b.arsip', '=', '1')
            ->get();
    }

    public static function updateLaporan($id, $params)
    {
        return DB::table('laporan')->where('id', $id)->update($params);
    }

    public static function delete($id)
    {
        return DB::table('laporan')->where('id', $id)->delete();
    }
}
