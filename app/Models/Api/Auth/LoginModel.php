<?php

namespace App\Models\Api\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoginModel extends DB
{
    public static function insert_app_log($params)
    {
        return DB::table('app_login')->insert($params);
    }

    // insert login attempt
    public static function insert_app_login_attempt($params)
    {
        return DB::table('app_login_attempt')->insert($params);
    }
}
