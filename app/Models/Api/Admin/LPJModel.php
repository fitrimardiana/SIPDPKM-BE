<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LPJModel extends DB
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
            ->select('a.id as lpj_id', 'a.bidang', 'a.no_kegiatan', 'a.status', 'a.komentar_admin', 'a.komentar_dosen', 'a.komentar_kadep', 'a.nama_file', 'a.nama_pj', 'a.nim_pj', 'a.no_hp', 'a.jabatan', 'b.nama', 'b.tgl_mulai', 'b.tgl_selesai', 'b.tempat', 'b.tujuan')
            ->join('kegiatan as b', 'a.id', 'b.lpj_id')
            ->where('b.jadwal', 'no')
            ->where('a.id', $id)
            ->first();
    }

    public static function updateLaporan($id, $params)
    {
        return DB::table('laporan')->where('id', $id)->update($params);
    }

    public static function updateKegiatan($id, $params)
    {
        return DB::table('kegiatan')->where('id', $id)->update($params);
    }

    public static function getLPJMahasiswa()
    {
        return DB::table('laporan as a')
            ->select('a.id as lpj_id', 'a.*', 'b.*')
            ->join('kegiatan as b', 'a.id', 'b.lpj_id')
            ->where('a.jenis', 'lpj')
            ->where('a.user_id', Auth::user()->id)
            ->get();
    }

    public static function getAllLPJ()
    {
        return DB::table('laporan as a')
            ->select('a.id as lpj_id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.jenis', 'lpj')
            ->where('a.arsip', '!=', '1')
            ->get();
    }
    public static function getArsipLPJ()
    {
        return DB::table('laporan as a')
            ->select('a.id as lpj_id', 'a.nama_pj', 'a.nim_pj', 'a.no_hp', 'a.jabatan', 'b.nama', 'b.tgl_mulai', 'b.tgl_selesai', 'b.tempat', 'b.tujuan')
            ->join('kegiatan as b', 'a.id', 'b.lpj_id')
            ->where('a.jenis', 'lpj')
            ->where('b.jadwal', 'no')
            ->where('a.status', 'Disetujui Kadep')
            ->where('a.arsip', '!=', '1')
            ->get();
    }


    public static function getKegiatan()
    {
        return DB::table('kegiatan as a')
            ->select('a.id as kegiatan_id', 'a.nama as nama_kegiatan', 'a.bidang')
            ->where('a.jadwal', 'no')
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
