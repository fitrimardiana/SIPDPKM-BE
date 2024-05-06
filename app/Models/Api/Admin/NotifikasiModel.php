<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotifikasiModel extends DB
{
    public static function getAllNotif()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.arsip', '!=', '1')
            ->get();
    }

    public static function getNotifMahasiswa()
    {
        return DB::table('laporan as a')
            ->select('a.id', 'a.nama_laporan', 'a.bidang', 'a.tgl_dikirim', 'a.tgl_lpj_dikirim', 'a.modified_date', 'a.jenis', 'a.status')
            ->where('a.arsip', '!=', '1')
            ->where('a.user_id', Auth::user()->id)
            ->get();
    }
}
