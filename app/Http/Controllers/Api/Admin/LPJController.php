<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Api\Admin\LPJModel;
use Illuminate\Support\Facades\Validator;

class LPJController extends Controller
{

    public function index()
    {
        $user_type = Auth::user()->type;
        if ($user_type == 'mahasiswa') {
            $lpj = LPJModel::getLPJMahasiswa();
        } else {
            $lpj = LPJModel::getAllLPJ();
        }
        $response = [
            "status"    => true,
            "message"   => 'Ok',
            "data"      => [
                "lpj" => $lpj
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }
    public function step1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_kegiatan'     => 'required',
            'bidang'     => 'required',
            'alur'   => 'required',
            'nama_laporan'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $param = [
            'no_kegiatan'      => $request->no_kegiatan,
            'nama_laporan'      => $request->nama_laporan,
            'bidang'      => $request->bidang,
            'alur'      => $request->alur,
            'jenis' => 'lpj',
            'created_date'  => date('Y-m-d H:i:s'),
        ];
        $lpj_id = LPJModel::insert($param);

        if ($lpj_id) {
            $response = [
                "status"    => true,
                "message"   => 'Data berhasil disimpan.',
                "data"      => [
                    "lpj_id" => $lpj_id
                ]
            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data gagal disimpan.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }

    public function step2($id)
    {

        $kegiatan = LPJModel::getKegiatan();
        $response = [
            "status"    => true,
            "data"   => [
                'kegiatan' => $kegiatan,
                'lpj_id' => $id
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function step2Update(Request $request, $id)
    {

        $param = [
            'lpj_id'      => $id,
            'created_date'  => date('Y-m-d H:i:s'),
        ];
        $kegiatan_id = LPJModel::updateKegiatan($request->kegiatan_id, $param);

        if ($kegiatan_id) {
            if ($request->hasFile('file')) {

                $file                       = $request->file('file');

                $file_name        = Str::slug("lpj", '-') . '-' . uniqid() . '.' . 'pdf';
                // upload path
                $upload_path      = '/file/lpj/';
                // cek folder                    
                if (!is_dir(public_path($upload_path))) {
                    // buat folder jika belum ada                        
                    mkdir(public_path($upload_path), 0755, true);
                }

                $new_file_name = $upload_path . $file_name;
                // upload process
                if (!$file->move(public_path($upload_path), $file_name)) {
                    // flash message
                    $response = [
                        "status"    => true,
                        "message"   => 'File gagal di upload.'
                    ];
                    return response()->json($response)->setStatusCode(200);
                }
            }
            $params = [
                'nama_pj'      => $request->nama_pj,
                'nim_pj'      => $request->nim_pj,
                'no_hp'      => $request->no_hp,
                'jabatan'      => $request->jabatan,
                'user_id' => Auth::user()->id,
                'nama_file'         => isset($new_file_name) ? $new_file_name : NULL,
                'created_date'  => date('Y-m-d H:i:s'),
            ];

            LPJModel::updateLaporan($id, $params);
            $response = [
                "status"    => true,
                "message"   => 'Data berhasil disimpan.',
                "data"      => [
                    "lpj_id" => $id
                ]
            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data gagal disimpan.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }

    public function prevStep3($id)
    {
        $detail_kegiatan = LPJModel::getDataById($id);
        if (!empty($detail_kegiatan)) {
            $response = [
                "status"    => true,
                "message"   => 'Ok',
                "data"      => [
                    "detail_kegiatan" => $detail_kegiatan
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

    public function step3(Request $request)
    {

        $param = [
            'tgl_lpj_dikirim'      => date('Y-m-d H:i:s'),
            'status'      => 'Diajukan',
            'user_id' => Auth::user()->id,
            'created_date'  => date('Y-m-d H:i:s'),
            'modified_date' => Date('Y-m-d H:i:s'),
        ];
        $update = LPJModel::updateLaporan($request->lpj_id, $param);

        if ($update) {
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

    public function detail($id)
    {
        $detail_kegiatan = LPJModel::getDataById($id);
        if (!empty($detail_kegiatan)) {
            $response = [
                "status"    => true,
                "message"   => 'Ok',
                "data"      => [
                    "detail_kegiatan" => $detail_kegiatan
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

    public function dosenApproval($id, Request $request)
    {
        $param = [
            'status' => $request->status,
            'komentar_dosen' => $request->komentar_dosen,
            'modified_date' => Date('Y-m-d H:i:s'),
        ];

        $update = LPJModel::updateLaporan($id, $param);

        if ($update) {
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

    public function kadepApproval($id, Request $request)
    {
        if ($request->status == 'Disetujui Kadep') {
            $param = [
                'status' => $request->status,
                'komentar_kadep' => $request->komentar_kadep,
                'tgl_lpj_disetujui' => Date('Y-m-d H:i:s'),
                'modified_date' => Date('Y-m-d H:i:s'),
            ];
        } else {
            $param = [
                'status' => $request->status,
                'komentar_kadep' => $request->komentar_kadep,
                'modified_date' => Date('Y-m-d H:i:s'),
            ];
        }

        $update = LPJModel::updateLaporan($id, $param);

        if ($update) {
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

    public function arsipIndex()
    {

        $LPJ = LPJModel::getArsipLPJ();

        $response = [
            "status"    => true,
            "message"   => 'Ok',
            "data"      => [
                "LPJ" => $LPJ
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function updateArsip($id)
    {
        $param = [
            'arsip' => "1",
            'modified_date' => date('Y-m-d H:i:s')
        ];

        $update = LPJModel::updateLaporan($id, $param);

        if ($update) {
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
        $delete = LPJModel::delete($id);
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

    public function tinjau($id)
    {
        $lpj = LPJModel::getById($id);
        if (!empty($lpj)) {
            $response = [
                "status"    => true,
                "message"   => 'Ok',
                "data"      => [
                    "lpj" => $lpj
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
        $riwayat = LPJModel::getById($id);

        $response = [
            'status' => true,
            'data' => [
                'riwayat' => $riwayat
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function update(Request $request)
    {
        if ($request->hasFile('file')) {

            $file                       = $request->file('file');

            $file_name        = Str::slug("lpj", '-') . '-' . uniqid() . '.' . 'pdf';
            // upload path
            $upload_path      = '/file/lpj/';


            // cek folder                    
            if (!is_dir(public_path($upload_path))) {
                // buat folder jika belum ada                        
                mkdir(public_path($upload_path), 0755, true);
            }

            $new_file_name = $upload_path . $file_name;
            // upload process
            if (!$file->move(public_path($upload_path), $file_name)) {
                // flash message
                $response = [
                    "status"    => true,
                    "message"   => 'File gagal di upload.'
                ];
                return response()->json($response)->setStatusCode(200);
            }
        }
        $params = [
            'user_id' => Auth::user()->id,
            'nama_file'         => isset($new_file_name) ? $new_file_name : NULL,
            'modified_date'  => date('Y-m-d H:i:s'),
        ];

        $update =  LPJModel::updateLaporan($request->id, $params);
        if ($update) {
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

    public function adminConfirm(Request $request)
    {
        $params = [
            'status' => $request->status,
            'komentar_admin' => $request->komentar_admin,
            'modified_date'  => date('Y-m-d H:i:s'),
        ];

        $update =  LPJModel::updateLaporan($request->id, $params);
        if ($update) {
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
}
