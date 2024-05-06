<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Admin\RiwayatModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayat = RiwayatModel::getAllData();
        $response = [
            "status"    => true,
            "message"   => 'Ok',
            "data"      => [
                "riwayat" => $riwayat
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function detail($id, $jenis)
    {
        if ($jenis == 'proposal') {
            $riwayat = RiwayatModel::getDetailProposalById($id);
        } else {
            $riwayat = RiwayatModel::getDetailLPJById($id);
        }

        $response = [
            'status' => true,
            'data' => [
                'riwayat' => $riwayat
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function detailDokumen($id)
    {
        $riwayat = RiwayatModel::getDataById($id);

        $response = [
            'status' => true,
            'data' => [
                'riwayat' => $riwayat
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function edit($id)
    {
        $riwayat = RiwayatModel::getDataById($id);

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

            if ($request->jenis == 'proposal') {
                $file_name        = Str::slug("proposal", '-') . '-' . uniqid() . '.' . 'pdf';
                // upload path
                $upload_path      = '/file/proposal/';
            } else {
                $file_name        = Str::slug("lpj", '-') . '-' . uniqid() . '.' . 'pdf';
                // upload path
                $upload_path      = '/file/lpj/';
            }

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

        $update =  RiwayatModel::updateLaporan($request->id, $params);
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
