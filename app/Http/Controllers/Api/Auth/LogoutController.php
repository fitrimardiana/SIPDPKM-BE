<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Auth\LoginModel;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LogoutController extends Controller
{

    public function logout(Request $request)
    {
        // log logout
        $params = [
            'user_id'   => Auth::user()->id,
            'status' => 'logout',
            'created_date'  => date('Y-m-d H:i:s'),
        ];
        LoginModel::insert_app_log($params);

        $user = $request->user();
        $user->currentAccessToken()->delete();

        // return json
        $response = [
            "status" => true,
            "message" => 'Berhasil'
        ];
        return response()->json($response)->setStatusCode(200);
    }
}
