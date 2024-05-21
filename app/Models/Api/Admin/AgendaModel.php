<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AgendaModel extends Model
{
    public static function getAgendaMahasiswa()
    {
        return DB::table('kegiatan as a')
            ->select('a.id', 'a.nama', 'c.bidang', 'a.tgl_mulai', 'a.tgl_selesai', 'a.status')
            ->leftjoin('laporan as b', 'b.id', 'a.lpj_id')
            ->leftjoin('laporan as c', 'c.id', 'a.proposal_id')
            // ->where('b.arsip', '!=', '1')
            // ->where('b.user_id', Auth::user()->id)
            // ->where('a.jadwal', 'no')
            ->where('c.status', 'Disetujui Kadep')
            ->get();
    }

    public static function getAllAgenda()
    {
        return DB::table('kegiatan as a')
            ->select('a.id', 'a.nama', 'c.bidang', 'a.tgl_mulai', 'a.tgl_selesai', 'a.status')
            ->leftjoin('laporan as b', 'b.id', 'a.lpj_id')
            ->leftjoin('laporan as c', 'c.id', 'a.proposal_id')
            // ->where('c.arsip', '=', '0')
            ->where('c.status', 'Disetujui Kadep')
            ->get();
    }

    public static function getDetailById($id)
    {
        return DB::table('kegiatan as a')
            ->select('a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.jam_mulai', 'a.jam_selesai', 'a.tempat', 'a.jadwal', 'b.bidang', 'b.no_kegiatan', 'b.status', 'a.tujuan', 'a.proposal_id', 'a.lpj_id')
            ->leftJoin('laporan as b', 'b.id', 'a.proposal_id')
            ->leftJoin('laporan as c', 'c.id', 'a.lpj_id')
            ->where('a.id', $id)
            ->get();
    }

    public static function getDetailProposalById($id)
    {
        return DB::table('laporan')->select('id', 'nama_file')->where('jenis', 'proposal')->where('id', $id)->first();
    }

    public static function getDetailLPJById($id)
    {
        return DB::table('laporan')->select('id', 'nama_file')->where('jenis', 'lpj')->where('id', $id)->first();
    }
}
