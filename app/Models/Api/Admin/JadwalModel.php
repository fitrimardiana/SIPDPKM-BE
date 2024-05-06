<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JadwalModel extends DB
{
    public static function getData()
    {
        return DB::table('kegiatan')->where('jadwal', 'yes')->get();
    }

    public static function insert($params)
    {
        return DB::table('kegiatan')->insert($params);
    }

    public static function getDataById($id)
    {
        return DB::table('kegiatan')->where('id', $id)->first();
    }

    public static function update($id, $params)
    {
        return DB::table('kegiatan')->where('id', $id)->update($params);
    }

    public static function getProposal()
    {
        return DB::table('laporan as a')
            ->select('b.nama', 'a.id as proposal_id')
            ->join('kegiatan as b', 'b.proposal_id', 'a.id')
            ->where('a.jenis', 'proposal')
            ->where('b.created_by', NULL)
            ->get();
    }

    public static function getLPJ()
    {
        return DB::table('laporan as a')
            ->select('b.nama', 'a.id as lpj_id')
            ->join('kegiatan as b', 'b.lpj_id', 'a.id')
            ->where('a.jenis', 'lpj')
            ->where('b.created_by', NULL)
            ->get();
    }

    public static function getDetailById($id)
    {
        return DB::table('kegiatan as a')
            ->select('a.id', 'a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.jam_mulai', 'a.jam_selesai', 'a.tempat', 'b.bidang', 'b.no_kegiatan', 'b.status', 'a.tujuan', 'a.proposal_id', 'a.lpj_id')
            ->leftJoin('laporan as b', 'b.id', 'a.proposal_id')
            ->leftJoin('laporan as c', 'c.id', 'a.lpj_id')
            ->where('a.id', $id)
            ->get();
    }

    public static function getDetailProposalById($id)
    {
        return DB::table('laporan')->where('jenis', 'proposal')->where('id', $id)->first();
    }

    public static function getDetailLPJById($id)
    {
        return DB::table('laporan')->where('jenis', 'lpj')->where('id', $id)->first();
    }

    public static function delete($id)
    {
        return DB::table('kegiatan')->where('id', $id)->delete();
    }
}
