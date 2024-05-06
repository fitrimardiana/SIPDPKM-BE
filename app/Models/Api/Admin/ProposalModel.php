<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProposalModel extends DB
{
    public static function insertKegiatan($params)
    {
        if (DB::table('kegiatan')->insert($params)) {
            return DB::getPdo()->lastInsertId();
        } else {
            return '';
        }
    }

    public static function insert($params)
    {
        if (DB::table('laporan')->insert($params)) {
            return DB::getPdo()->lastInsertId();
        } else {
            return '';
        }
    }

    public static function getDataById($id)
    {
        return DB::table('laporan as a')
            ->select('a.id as proposal_id', 'a.status', 'a.komentar_admin', 'a.komentar_dosen', 'a.komentar_kadep', 'a.nama_file', 'a.nama_pj', 'a.nim_pj', 'a.no_hp', 'a.jabatan', 'b.nama', 'b.tgl_mulai', 'b.tgl_selesai', 'b.tempat', 'b.tujuan')
            ->join('kegiatan as b', 'a.id', 'b.proposal_id')
            ->where('b.jadwal', 'no')
            ->where('a.id', $id)
            ->first();
    }

    public static function updateLaporan($id, $params)
    {
        return DB::table('laporan')->where('id', $id)->update($params);
    }

    public static function getProposalMahasiswa()
    {
        return DB::table('laporan as a')
            ->select('a.id as proposal_id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.jenis', 'proposal')
            ->where('a.arsip', '!=', '1')
            ->where('a.user_id', Auth::user()->id)
            ->get();
    }

    public static function getAllProposal()
    {
        return DB::table('laporan as a')
            ->select('a.id as proposal_id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.jenis', 'proposal')
            ->where('a.arsip', '!=', '1')
            ->get();
    }

    public static function getArsipProposal()
    {
        return DB::table('laporan as a')
            ->select('a.id as proposal_id', 'a.nama_pj', 'a.nim_pj', 'a.no_hp', 'a.jabatan', 'b.nama', 'b.tgl_mulai', 'b.tgl_selesai', 'b.tempat', 'b.tujuan')
            ->join('kegiatan as b', 'a.id', 'b.proposal_id')
            ->where('a.jenis', 'proposal')
            ->where('b.jadwal', 'no')
            ->where('a.status', 'Disetujui Kadep')
            ->where('a.arsip', '!=', '1')
            ->get();
    }

    public static function delete($id)
    {
        return DB::table('laporan')->where('id', $id)->delete();
    }

    public static function getById($id)
    {
        return DB::table('laporan')->where('id', $id)->first();
    }
}
