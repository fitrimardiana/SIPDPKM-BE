<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Api\Admin\AkunModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use Throwable;

class AkunController extends Controller
{

    public $currentUrl;

    public function index()
    {
        $akun = AkunModel::getAllData();
        $response = [
            "status"    => true,
            "data"   => [
                'akun' => $akun
            ]
        ];


        return response()->json($response)->setStatusCode(200);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'nama'     => 'required',
            'tipe'   => 'required',
            'no_hp'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = AkunModel::getByEmail($request->email);
        if (!empty($user)) {
            // flash message
            $response = [
                'message' => 'Data gagal disimpan. Email ' . $request->email . ' sudah terdaftar!'
            ];
            return response()->json($response)->setStatusCode(200);
        }

        $param = [
            'email'      => $request->email,
            'type'      => $request->tipe,
            'password'      => Hash::make('12345678'),
            'created_at'  => date('Y-m-d H:i:s'),
        ];
        $user_id = AkunModel::insert($param);

        $params = [
            'no_identitas' => $request->no_identitas,
            'no_hp' => $request->no_hp,
            'nama' => $request->nama,
            'user_id' => $user_id,
            'jabatan' => $request->jabatan,
            'bidang' => $request->bidang,
            'created_date'  => date('Y-m-d H:i:s'),
        ];
        $insert = AkunModel::insertDetail($params);

        //return response
        if ($insert) {
            $response = [
                "status"    => true,
                "message"   => 'Data berhasil disimpan.',

            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data gagal disimpan.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }

    public function dataById($id)
    {

        $data = AkunModel::getAkunById($id);
        if (!empty($data)) {
            $response = [
                "status"    => true,
                "message"   => 'Ok',
                "data"      => [
                    "data" => $data
                ]
            ];

            return response()->json($response)->setStatusCode(200);
        } else {
            // return json
            $response = [
                "status"    => false,
                "message"   => 'Data tidak ditemukan.'
            ];

            return response()->json($response)->setStatusCode(200);
        }
    }

    public function edit($id)
    {
        $data = AkunModel::getDataById($id);

        if ($data) {
            $response = [
                "status"    => true,
                "data"   => [
                    'akun' => $data
                ]
            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data tidak ditemukan.'
            ];
        }
        return response()->json($response)->setStatusCode(200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $param = [
            'email'      => $request->email,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
        AkunModel::update($request->id, $param);

        $params = [
            'no_identitas' => $request->no_identitas,
            'no_hp' => $request->no_hp,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'bidang' => $request->bidang,
            'modified_date'  => date('Y-m-d H:i:s'),
        ];
        $insert = AkunModel::updateDetail($request->id, $params);

        //return response
        if ($insert) {
            $response = [
                "status"    => true,
                "message"   => 'Data berhasil disimpan.',

            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data gagal disimpan.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }

    public function delete($id)
    {
        $delete = AkunModel::delete($id);
        if ($delete) {
            $response = [
                "status"    => true,
                "message"   => 'Data berhasil dihapus.',
            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data gagal dihapus.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }

    public function profil()
    {

        $data = AkunModel::getProfilById();
        if (!empty($data)) {
            $response = [
                "status"    => true,
                "message"   => 'Ok',
                "data"      => [
                    "data" => $data
                ]
            ];

            return response()->json($response)->setStatusCode(200);
        } else {
            // return json
            $response = [
                "status"    => false,
                "message"   => 'Data tidak ditemukan.'
            ];

            return response()->json($response)->setStatusCode(200);
        }
    }

    public function updateProfil(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $param = [
            'email'      => $request->email,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
        AkunModel::update($request->id, $param);

        $params = [
            'no_hp' => $request->no_hp,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'bidang' => $request->bidang,
            'modified_date'  => date('Y-m-d H:i:s'),
        ];
        $insert = AkunModel::updateDetail($request->id, $params);

        //return response
        if ($insert) {
            $response = [
                "status"    => true,
                "message"   => 'Data berhasil disimpan.',

            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data gagal disimpan.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'       => 'required',
            'password'          => 'required|min:8|max:20'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $param = [
            'password'      =>  Hash::make($request->password),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];
        $update = AkunModel::update($request->email, $param);

        //return response
        if ($update) {
            $response = [
                "status"    => true,
                "message"   => 'Password berhasil diubah.',

            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Passsword gagal diubah.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }

    public function lupaPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'       => 'required',
            'no_hp'          => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = AkunModel::getUserBy($request->email, $request->no_hp);

        if (empty($user)) {
            $response = [
                "status"    => true,
                "message"   => 'Akun tidak ditemukan.',

            ];
        } else {
            $data = [
                'name' => 'Ubah kata sandi',
                'body' => 'Testing',
                'user_email' => $user->email,
                'user_name' => $user->nama,
                'reset_url' => "ganti sesuai url web nya"
            ];
            // try send mail
            Mail::to($data['user_email'])->send(new SendEmail($data));

            $response = [
                "status"    => true,
                "message"   => 'Permintaan perubahan kata sandi terkirim.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }
}
