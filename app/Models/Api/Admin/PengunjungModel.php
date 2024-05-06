<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengunjungModel extends DB
{
    public static function getAllData()
    {
        return DB::table('laporan as a')
        ->select(
            'a.id',
            'a.nama_laporan',
            'a.bidang',
            'a.tgl_dikirim',
            'a.tgl_lpj_dikirim',
            'a.modified_date',
            'a.jenis',
            'a.status',
            DB::raw('CASE a.jenis WHEN "proposal" THEN b.proposal_id WHEN "lpj" THEN b.lpj_id END as relevant_id'), // Kunci yang sesuai
            'b.tgl_mulai' // Menampilkan tgl_mulai
        )
        ->leftJoin('kegiatan as b', DB::raw('CASE a.jenis WHEN "proposal" THEN a.id WHEN "lpj" THEN a.id END'), '=', DB::raw('CASE a.jenis WHEN "proposal" THEN b.proposal_id WHEN "lpj" THEN b.lpj_id END')) // Menghubungkan dengan kondisi yang sesuai
        ->where('a.arsip', '!=', '1') // Kondisi arsip tidak sama dengan 1
        ->where('a.status', 'Disetujui Kadep') // Status disetujui oleh Kadep
        ->get();
    }

    public static function getDataProposal()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.arsip', '!=', '1')
            ->where('a.jenis', 'proposal')
            ->where ('a.status', 'Disetujui Kadep')
            ->get();
    }

    public static function getDataLPJ()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.arsip', '!=', '1')
            ->where('a.jenis', 'lpj')
            ->where ('a.status', 'Disetujui Kadep')
            ->get();
    }

    public static function getDetailById($id)
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.jenis', 'a.nama_file', 'a.komentar_dosen', 'a.komentar_kadep', 'a.status', 'a.bidang', 'a.no_kegiatan')
            ->where('a.arsip', '!=', '1')
            ->where('a.id', $id)
            ->where ('a.status', 'Disetujui Kadep')
            ->get();
    }

    public static function getDetailProposalById($id)
    {
        return DB::table('laporan as b')
            ->select('b.id as proposal_id', 'b.nama_pj', 'b.nim_pj', 'b.jabatan', 'b.no_hp', 'a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.tempat', 'b.bidang', 'b.no_kegiatan', 'b.status', 'a.tujuan', 'b.nama_file')
            ->leftJoin('kegiatan as a', 'b.id', 'a.proposal_id')
            ->where('b.id', $id)
            // ->where('jadwal', 'no')
            // ->where('b.arsip', '!=', '1')
            ->where ('b.status', 'Disetujui Kadep')
            ->get();
    }

    public static function getDetailLpjById($id)
    {
        return DB::table('laporan as b')
            ->select('b.id as lpj_id', 'b.nama_pj', 'b.nim_pj', 'b.jabatan', 'b.no_hp', 'a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.tempat', 'b.bidang', 'b.no_kegiatan', 'b.status', 'a.tujuan', 'b.nama_file')
            ->leftJoin('kegiatan as a', 'b.id', 'a.lpj_id')
            ->where('b.id', $id)
            // ->where('jadwal', 'no')
            // ->where('b.arsip', '!=', '1')
            ->where ('b.status', 'Disetujui Kadep')
            ->get();
    }

    public static function getAllAgenda()
    {
        return DB::table('kegiatan as a')
            ->select('a.id', 'a.nama', 'c.bidang', 'a.tgl_mulai', 'a.tgl_selesai', 'a.status', 'a.jam_mulai', 'jam_selesai')
            ->leftjoin('laporan as c', 'c.id', 'a.proposal_id')
            // ->where('c.arsip', '!=', '1')
            // ->where('a.jadwal', 'no')
            ->where('c.status', 'Disetujui Kadep')
            ->get();
    }

    public static function getDataJadwal()
    {
        return DB::table('kegiatan')->where('jadwal', 'yes')->get();
    }

    public static function getAllJadwal()
    {
        return DB::table('kegiatan as a')
            ->select('a.id', 'a.nama', 'c.bidang', 'a.tgl_mulai', 'a.tgl_selesai', 'a.status', 'jam_mulai', 'jam_selesai')
            ->leftjoin('laporan as c', 'c.id', 'a.proposal_id')
            // ->where('c.arsip', '!=', '1')
            ->where('a.jadwal', 'yes')
            ->get();
    }

    public static function getDetailAgendaById($id)
    {
        return DB::table('kegiatan as a')
            ->select('a.nama', 'a.tgl_mulai', 'a.tgl_selesai', 'a.jam_mulai', 'a.jam_selesai', 'a.tempat', 'b.bidang', 'b.no_kegiatan', 'c.status', 'a.tujuan', 'a.proposal_id', 'a.lpj_id')
            ->leftJoin('laporan as b', 'b.id', 'a.proposal_id')
            ->leftJoin('laporan as c', 'c.id', 'a.lpj_id')
            ->where('a.id', $id)
            ->get();
    }

    public static function getDetailFileById($id)
    {
        return DB::table('laporan')
        ->select('id', 'nama_file', 'status')
        ->where('id', $id)->first()
        ->where ('status', 'Disetujui Kadep');
    }
}
