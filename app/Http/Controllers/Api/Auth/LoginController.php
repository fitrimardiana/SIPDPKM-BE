<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Auth\LoginModel;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        // Validate & auto redirect when fail
        $rules = [
            'email'       => 'required',
            'password'          => 'required|min:8|max:20'
        ];
        $this->validate($request, $rules);

        // if (!LoginModel::getRoleChecker($request->username)) {
        //     // return json
        //     $response = [
        //         "status"    => false,
        //         "message"   => 'Login gagal. Role Tidak Diizinkan.'
        //     ];
        //     return response()->json($response)->setStatusCode(200);
        // }

        // process
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            // log login
            $params = [
                'user_id'   => Auth::user()->id,
                'status'    => 'login',
                'created_date'      => date('Y-m-d H:i:s'),
            ];
            // insert
            LoginModel::insert_app_log($params);

            // get user
            $user = User::where('email', $request->email)->first();

            // return json
            $response = [
                "status"    => true,
                "message"   => 'Login berhasil.',
                "data" => [
                    "access_token"  => $user->createToken($request->email)->plainTextToken,
                    "token_type"    => "Bearer"
                ]
            ];

            // Log::info('API : Login berhasil', $params);

            return response()->json($response)->setStatusCode(200);
        } else {
            $params = [
                'email'   => $request->email,
                'password'      => $request->password,
                'created_date'  => date('Y-m-d H:i:s')
            ];
            // insert
            LoginModel::insert_app_login_attempt($params);

            // return json
            $response = [
                "status"    => false,
                "message"   => 'Login gagal. Silahkan cek kembali ID Pengguna & Kata Sandi Anda.'
            ];

            // Log::error('API : Login gagal', $params);

            return response()->json($response)->setStatusCode(200);
        }
    }
}
