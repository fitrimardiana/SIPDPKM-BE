<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Admin\NotifikasiModel;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $user_type = Auth::user()->type;
        if ($user_type == 'mahasiswa') {
            $notifikasi = NotifikasiModel::getNotifMahasiswa();
        } else {
            $notifikasi = NotifikasiModel::getAllNotif();
        }
        $response = [
            "status"    => true,
            "message"   => 'Ok',
            "data"      => [
                "notifikasi" => $notifikasi
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }
}
