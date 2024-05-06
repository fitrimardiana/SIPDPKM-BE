<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Admin\RevisiModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RevisiController extends Controller
{
    public function index()
    {
        $revisi = RevisiModel::getData();

        $response = [
            "status"    => true,
            "data" => [
                'revisi' => $revisi
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function detail($id)
    {
        $revisi = RevisiModel::getDataById($id);

        $response = [
            "status"    => true,
            "data" => [
                'revisi' => $revisi
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function edit($id)
    {
        $riwayat = RevisiModel::getDataById($id);

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

        $update =  RevisiModel::updateLaporan($request->id, $params);
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
