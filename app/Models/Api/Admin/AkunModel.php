<?php

namespace App\Models\Api\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;

class AkunModel extends DB
{

    public static function getAllData()
    {
        return DB::table('users as a')
            ->select('b.nama', 'b.bidang', 'b.no_hp', 'a.type', 'a.email', 'a.id')
            ->join('user_detail as b', 'b.user_id', 'a.id')
            ->get();
    }
    public static function insert($params)
    {
        if (DB::table('users')->insert($params)) {
            return DB::getPdo()->lastInsertId();
        } else {
            return '';
        }
    }

    public static function insertDetail($params)
    {
        return DB::table('user_detail')->insert($params);
    }

    public static function getByEmail($email)
    {
        return DB::table('users')->where('email', $email)->first();
    }

    public static function getDataById($id)
    {
        return DB::table('users as a')
            ->select('a.id', 'b.nama', 'b.bidang', 'b.no_hp', 'a.email', 'b.jabatan', 'b.no_identitas')
            ->join('user_detail as b', 'b.user_id', 'a.id')
            ->where('a.id', $id)
            ->first();
    }

    public static function update($id, $params)
    {
        return DB::table('users')->where('email', $id)->update($params);
    }

    public static function updateDetail($id, $params)
    {
        return DB::table('user_detail')->where('user_id', $id)->update($params);
    }


    public static function delete($id)
    {
        return DB::table('users')->where('id', $id)->delete();
    }

    public static function getProfilById()
    {
        return DB::table('users as a')
            ->select('a.id', 'a.email', 'b.nama', 'b.no_hp', 'b.jabatan', 'b.bidang', 'a.type')
            ->Leftjoin('user_detail as b', 'b.user_id', 'a.id')
            ->where('a.id', Auth::user()->id)
            ->first();
    }

    public static function getUserBy($email, $no_hp)
    {
        return DB::table('users as a')
            ->Leftjoin('user_detail as b', 'b.user_id', 'a.id')
            ->where('a.email', $email)
            ->where('b.no_hp', $no_hp)
            ->first();
    }
}